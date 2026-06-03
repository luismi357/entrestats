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
        $userId = request()->user()->id;

        return response()->json(
            Message::with('user')
                ->where(function ($q) use ($userId) {
                    $q->where('user_id', $userId)->whereNull('receiver_id')
                      ->orWhere('receiver_id', $userId);
                })
                ->orderBy('created_at', 'desc')
                ->take(50)
                ->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate(['content' => 'required|string']);

        $message = Message::create([
            'user_id' => $request->user()->id,
            'receiver_id' => null,
            'content' => $request->content,
        ]);

        $message->load('user');

        return response()->json($message, 201);
    }
}
