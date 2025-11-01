<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use App\Models\TelegramMessage;

class NewTelegramMessage implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(TelegramMessage $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('telegram-channel');
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'chat_id' => $this->message->chat_id,
            'text' => $this->message->message_text,
            'is_from_user' => $this->message->is_from_user,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }

    public function broadcastAs(): string
    {
        return 'NewTelegramMessage';
    }
}
