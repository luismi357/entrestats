<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class ChatApiController extends Controller
{
    public function index()
    {
        return response()->json(
            Message::with('user')->orderBy('created_at', 'desc')->take(50)->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate(['content' => 'required|string']);

        $message = Message::create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        $message->load('user');
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message, 201);
    }
}
