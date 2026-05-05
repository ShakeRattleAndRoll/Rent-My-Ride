<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserRelation;
use App\Models\Message;
use App\Models\User;


class MessageController extends Controller
{
    public function index($receiverId = null)
    {
        $authId = Auth::id();

        $myRelations = \App\Models\UserRelation::where('user_id', $authId)->get();

        $contacts = Message::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->get()
            ->map(function ($message) use ($authId) {
                return $message->sender_id == $authId ? $message->receiver : $message->sender;
        })->unique('id')->map(function ($contact) use ($authId, $myRelations) {
            $contact->is_muted = $myRelations->where('target_id', $contact->id)->where('type', 'mute')->first();
            $contact->is_blocked_by_me = $myRelations->where('target_id', $contact->id)->where('type', 'block')->first();
            
            $contact->unread_count = Message::where('sender_id', $contact->id)
                ->where('receiver_id', $authId)
                ->where('is_read', false)
                ->count();

            return $contact;
        });;

        $messages = [];
        $activeContact = null;
        $chatBlocked = false;
        
        if ($receiverId) {
            $activeContact = User::findOrFail($receiverId);

            Message::where('sender_id', $receiverId)
                ->where('receiver_id', $authId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $chatBlocked = \App\Models\UserRelation::where('type', 'block')
            ->where(function($q) use ($authId, $receiverId) {
                $q->where(function($inner) use ($authId, $receiverId) {
                    $inner->where('user_id', $authId)->where('target_id', $receiverId);
                })->orWhere(function($inner) use ($authId, $receiverId) {
                    $inner->where('user_id', $receiverId)->where('target_id', $authId);
                });
            })->exists();

            $messages = Message::where(function($query) use ($receiverId) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $receiverId);
            })->orWhere(function($query) use ($receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', Auth::id());
            })->orderBy('created_at', 'asc')->get();
    
        }

        return view('message.message', compact('contacts', 'messages', 'activeContact', 'chatBlocked'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string|max:5000',
        ]);

        $authId = Auth::id();
        $receiverId = $request->receiver_id;

        $isBlocked = \App\Models\UserRelation::where('type', 'block')
        ->where(function($query) use ($authId, $receiverId) {
            $query->where(function($q) use ($authId, $receiverId) {
                $q->where('user_id', $authId)->where('target_id', $receiverId);
            })->orWhere(function($q) use ($authId, $receiverId) {
                $q->where('user_id', $receiverId)->where('target_id', $authId);
            });
        })->exists();

        if ($isBlocked) {
            return back()->with('error', 'Message could not be sent.'); 
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);

        return back();
    }

    public function toggleMute($targetId)
    { return $this->toggleRelation($targetId, 'mute');}

    public function toggleBlock($targetId)
    { return $this->toggleRelation($targetId, 'block');}

    private function toggleRelation($targetId, $type)
    {
        $authId = Auth::id();
        
        $relation = UserRelation::where('user_id', $authId)
            ->where('target_id', $targetId)
            ->where('type', $type)
            ->first();

        if ($relation) {
            $relation->delete();
            $status = "un{$type}d";
        } else {
            UserRelation::create([
                'user_id' => $authId,
                'target_id' => $targetId,
                'type' => $type,
            ]);
            $status = "{$type}d";
        }

        return back()->with('status', "User successfully $status");
    }
}