<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendTelegramReply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $chatId;
    public $text;

    public function __construct($chatId, $text)
    {
        $this->chatId = $chatId;
        $this->text = $text;
    }

    public function handle()
    {
        $token = config('services.telegram.bot_token');

        Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $this->chatId,
            'text' => $this->text
        ]);
    }
}
