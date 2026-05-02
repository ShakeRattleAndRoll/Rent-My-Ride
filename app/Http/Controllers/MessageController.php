<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\User;


class MessageController extends Controller
{
    public function index($receiverId = null)
    {
        $authId = Auth::id();

        $contacts = Message::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->get()
            ->map(function ($message) use ($authId) {
                return $message->sender_id == $authId ? $message->receiver : $message->sender;
            })->unique('id');

        $messages = [];
        $activeContact = null;
        
        if ($receiverId) {
        $activeContact = User::findOrFail($receiverId);

        $messages = Message::where(function($query) use ($receiverId) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $receiverId);
        })->orWhere(function($query) use ($receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();
    
    }

        return view('message.message', compact('contacts', 'messages', 'activeContact'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string|max:5000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'body' => $request->body,
        ]);

        return back();
    }

}