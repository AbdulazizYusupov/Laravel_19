<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $message;
    public $fileUrl;
    public function __construct($message)
    {
        $this->message = $message;
        $this->fileUrl = $message->file ? asset('storage/' . $message->file) : null;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('xabar.' . $this->message->chat_id),
        ];
    }


    public function broadcastWith()
    {
        return [
            'text' => $this->message->text,
            'sender' => $this->message->sender,
            'file' => $this->fileUrl
        ];
    }
}
