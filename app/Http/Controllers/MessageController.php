<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\UserRelation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Message inbox and active conversation view
    public function index($receiverId = null)
    {
        $authId = Auth::id();
        $myRelations = UserRelation::where('user_id', $authId)->get();

        $contacts = Message::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->get()
            ->map(function ($message) use ($authId) {
                return $message->sender_id == $authId ? $message->receiver : $message->sender;
            })
            ->unique('id')
            ->map(function ($contact) use ($authId, $myRelations, $receiverId) {
                $contact->is_muted = $myRelations
                    ->where('target_id', $contact->id)
                    ->where('type', 'mute')
                    ->first();

                $contact->is_blocked_by_me = $myRelations
                    ->where('target_id', $contact->id)
                    ->where('type', 'block')
                    ->first();

                $contact->unread_count = ($receiverId && $contact->id == $receiverId)
                    ? 0
                    : Message::where('sender_id', $contact->id)
                        ->where('receiver_id', $authId)
                        ->where('is_read', false)
                        ->count();

                return $contact;
            });

        $messages = [];
        $activeContact = null;
        $chatBlocked = false;

        if ($receiverId) {
            $activeContact = User::findOrFail($receiverId);

            Message::where('sender_id', $receiverId)
                ->where('receiver_id', $authId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $chatBlocked = UserRelation::where('type', 'block')
                ->where(function ($query) use ($authId, $receiverId) {
                    $query->where(function ($inner) use ($authId, $receiverId) {
                        $inner->where('user_id', $authId)->where('target_id', $receiverId);
                    })->orWhere(function ($inner) use ($authId, $receiverId) {
                        $inner->where('user_id', $receiverId)->where('target_id', $authId);
                    });
                })
                ->exists();

            $messages = Message::where(function ($query) use ($receiverId) {
                $query->where('sender_id', Auth::id())
                    ->where('receiver_id', $receiverId);
            })
                ->orWhere(function ($query) use ($receiverId) {
                    $query->where('sender_id', $receiverId)
                        ->where('receiver_id', Auth::id());
                })
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('message.message', compact('contacts', 'messages', 'activeContact', 'chatBlocked'));
    }

    // Send a message
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string|max:5000',
        ]);

        $authId = Auth::id();
        $receiverId = $request->receiver_id;

        $isBlocked = UserRelation::where('type', 'block')
            ->where(function ($query) use ($authId, $receiverId) {
                $query->where(function ($inner) use ($authId, $receiverId) {
                    $inner->where('user_id', $authId)->where('target_id', $receiverId);
                })->orWhere(function ($inner) use ($authId, $receiverId) {
                    $inner->where('user_id', $receiverId)->where('target_id', $authId);
                });
            })
            ->exists();

        if ($isBlocked) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Message could not be sent.'], 403);
            }

            return back()->with('error', 'Message could not be sent.');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->formatMessage($message),
            ], 201);
        }

        return back();
    }

    // Live thread refresh
    public function thread($receiverId)
    {
        $authId = Auth::id();
        User::findOrFail($receiverId);

        $chatBlocked = UserRelation::where('type', 'block')
            ->where(function ($query) use ($authId, $receiverId) {
                $query->where(function ($inner) use ($authId, $receiverId) {
                    $inner->where('user_id', $authId)->where('target_id', $receiverId);
                })->orWhere(function ($inner) use ($authId, $receiverId) {
                    $inner->where('user_id', $receiverId)->where('target_id', $authId);
                });
            })
            ->exists();

        Message::where('sender_id', $receiverId)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($query) use ($authId, $receiverId) {
            $query->where('sender_id', $authId)
                ->where('receiver_id', $receiverId);
        })
            ->orWhere(function ($query) use ($authId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages->map(fn ($message) => $this->formatMessage($message)),
            'chat_blocked' => $chatBlocked,
        ]);
    }

    // Unread message counters
    public function notifications()
    {
        $authId = Auth::id();

        $mutedUserIds = UserRelation::where('user_id', $authId)
            ->where('type', 'mute')
            ->pluck('target_id');

        $totalUnreadMessages = Message::where('receiver_id', $authId)
            ->where('is_read', false)
            ->whereNotIn('sender_id', $mutedUserIds)
            ->count();

        $contacts = Message::where('receiver_id', $authId)
            ->where('is_read', false)
            ->whereNotIn('sender_id', $mutedUserIds)
            ->selectRaw('sender_id, COUNT(*) as unread_count')
            ->groupBy('sender_id')
            ->get()
            ->map(fn ($message) => [
                'id' => $message->sender_id,
                'unread_count' => $message->unread_count,
            ]);

        return response()->json([
            'total_unread_messages' => $totalUnreadMessages,
            'contacts' => $contacts,
        ]);
    }

    // Mute and block controls
    public function toggleMute($targetId)
    {
        return $this->toggleRelation($targetId, 'mute');
    }

    public function toggleBlock($targetId)
    {
        return $this->toggleRelation($targetId, 'block');
    }

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

        if (request()->expectsJson()) {
            session()->flash('status', "User successfully $status");

            return response()->json([
                'redirect' => url()->previous(),
            ]);
        }

        return back()->with('status', "User successfully $status");
    }

    // User search for starting conversations
    public function searchUsers(Request $request)
    {
        $q = $request->query('q', '');

        $users = User::where('id', '!=', Auth::id())
            ->where(function ($query) use ($q) {
                $query->where('username', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$q}%"]);
            })
            ->select('id', 'username', 'first_name', 'last_name', 'profile_picture')
            ->limit(8)
            ->get()
            ->map(fn ($user) => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => trim("{$user->first_name} {$user->last_name}"),
                'avatar' => $user->profile_picture
                    ? asset('storage/' . $user->profile_picture)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->username),
            ]);

        return response()->json($users);
    }

    // Shared message response format
    private function formatMessage(Message $message)
    {
        return [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'receiver_id' => $message->receiver_id,
            'body' => $message->body,
            'time' => $message->created_at->timezone('Asia/Manila')->format('g:i A'),
        ];
    }
}
