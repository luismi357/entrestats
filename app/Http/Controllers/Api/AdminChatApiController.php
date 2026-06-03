<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class AdminChatApiController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()?->email !== 'luismiortegasancho@gmail.com') {
                abort(403, 'No autorizado');
            }
            return $next($request);
        });
    }

    public function users()
    {
        $userIds = Message::whereNull('receiver_id')->pluck('user_id')->unique();

        $users = User::whereIn('id', $userIds)->get(['id', 'name', 'email']);

        $users->each(function ($u) {
            $last = Message::where(function ($q) use ($u) {
                $q->where('user_id', $u->id)->whereNull('receiver_id')
                  ->orWhere('receiver_id', $u->id);
            })->orderBy('created_at', 'desc')->first();
            $u->last_message = $last ? $last->content : null;
            $u->last_time = $last ? $last->created_at : null;
        });

        return response()->json($users->sortByDesc('last_time')->values());
    }

    public function messages($userId)
    {
        $messages = Message::with('user')
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->whereNull('receiver_id')
                  ->orWhere('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function store(Request $request, $userId)
    {
        $request->validate(['content' => 'required|string']);

        $message = Message::create([
            'user_id' => $request->user()->id,
            'receiver_id' => $userId,
            'content' => $request->content,
        ]);

        $message->load('user');

        return response()->json($message, 201);
    }
}
