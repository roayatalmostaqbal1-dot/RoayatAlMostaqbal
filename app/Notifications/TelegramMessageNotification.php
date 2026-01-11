<?php

namespace App\Notifications;

use App\Models\TelegramMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class TelegramMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    public function __construct(TelegramMessage $message)
    {
        $this->message = $message;
    }

    public function via($notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'telegram_message',
            'message_id' => $this->message->id,
            'chat_id' => $this->message->telegram_chat_id,
            'sender_name' => $this->message->chat->full_name,
            'text' => $this->message->message_text,
            'sent_at' => $this->message->sent_at,
            'photo_url' => $this->message->chat->photo_url,
        ];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
