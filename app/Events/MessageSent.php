<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use App\Models\User;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public $user;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->user = $message->user;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat');
    }
}

