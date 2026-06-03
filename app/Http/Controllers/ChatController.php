<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        $userId = Auth::id();

        return Message::with('user')
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->whereNull('receiver_id')
                  ->orWhere('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->take(50)
            ->get();
    }

    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'user_id' => Auth::id(),
            'receiver_id' => null,
            'content' => $request->input('content'),
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
