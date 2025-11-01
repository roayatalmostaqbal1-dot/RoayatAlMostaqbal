<?php

namespace App\Http\Controllers\Api\V1\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ProcessTelegramMessage;
use App\Models\TelegramMessage;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $chatId = $request->input('chat_id');
        $text = $request->input('message');

        $message = TelegramMessage::create([
            'chat_id' => $chatId,
            'message_text' => $text,
            'is_from_user' => true,
            'status' => 'pending',
        ]);

        // إرسال الـ Job لمعالجة الرسالة
        ProcessTelegramMessage::dispatch($message);

        return response()->json(['status' => 'ok']);
    }
}
