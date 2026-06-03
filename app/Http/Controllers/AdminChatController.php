<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()?->email !== 'luismiortegasancho@gmail.com') {
                abort(403, 'No autorizado');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $userIds = Message::whereNull('receiver_id')->pluck('user_id')->unique();
        $users = User::whereIn('id', $userIds)->get();

        return view('admin.chat', compact('users'));
    }

    public function conversation($userId)
    {
        $user = User::findOrFail($userId);

        $messages = Message::with('user')
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->whereNull('receiver_id')
                  ->orWhere('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.conversation', compact('user', 'messages'));
    }

    public function sendMessage(Request $request, $userId)
    {
        $message = Message::create([
            'user_id' => Auth::id(),
            'receiver_id' => $userId,
            'content' => $request->input('content'),
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return redirect()->back();
    }
}
