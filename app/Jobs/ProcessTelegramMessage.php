<?php

namespace App\Jobs;

use App\Models\TelegramMessage;
use App\Events\NewTelegramMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    public function __construct(TelegramMessage $message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        $this->message->status = 'processed';
        $this->message->save();

        // تحديث Cache
        $cacheKey = "chat:{$this->message->chat_id}";
        $messages = Cache::get($cacheKey, []);
        array_unshift($messages, $this->message);
        if(count($messages) > 50) array_pop($messages);
        Cache::put($cacheKey, $messages, 3600);

        // بث الحدث عبر Reverb
        event(new NewTelegramMessage($this->message));
    }
}
