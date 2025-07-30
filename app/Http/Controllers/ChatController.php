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
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request)
    {
        $message = Auth::user()->messages()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return ['status' => 'Message Sent!'];
    }

    public function store(Request $request)
    {
        try {
            // Valida los datos recibidos
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'content' => 'required|string',
            ]);

            // Guarda el mensaje
            $message = Message::create([
                'user_id' => $request->user_id,
                'content' => $request->content,
            ]);

            return response()->json(['message' => 'Mensaje enviado', 'data' => $message], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
