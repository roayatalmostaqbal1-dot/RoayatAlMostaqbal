<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TelegramMessage;
use App\Models\TelegramReply;
use App\Models\User;

class TelegramDemoSeeder extends Seeder
{
    public function run()
    {
        $user = User::factory()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@demo.com',
            'password' => bcrypt('password123')
        ]);

        for($i=1;$i<=10;$i++){
            $message = TelegramMessage::create([
                'chat_id' => 1000+$i,
                'user_id' => $user->id,
                'message_text' => "رسالة تجريبية رقم {$i}",
                'is_from_user' => true,
                'status' => 'processed'
            ]);

            for($j=1;$j<=rand(1,2);$j++){
                TelegramReply::create([
                    'telegram_message_id' => $message->id,
                    'reply_text' => "رد تجريبي {$j} على رسالة {$i}",
                    'status' => 'sent'
                ]);
            }
        }
    }
}
