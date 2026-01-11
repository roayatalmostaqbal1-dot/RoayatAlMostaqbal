<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\TelegramMessageReceived;
use App\Http\Controllers\Controller;
use App\Models\TelegramChat;
use App\Models\TelegramMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Update;

class TelegramWebhookController extends Controller
{
    /**
     * Handle incoming webhook from Telegram
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Get the update from Telegram
            $update = Telegram::bot()->commandsHandler(true);

            // Check if it's a message
            if (!$update->getMessage()) {
                return response()->json(['ok' => true], 200);
            }

            $message = $update->getMessage();
            $from = $message->getFrom();

            // Extract user information
            $telegramUserId = (string) $from->getId();
            $username = $from->getUsername();
            $firstName = $from->getFirstName();
            $lastName = $from->getLastName();

            // Get or create chat record
            $chat = TelegramChat::firstOrCreate(
                ['telegram_user_id' => $telegramUserId],
                [
                    'telegram_username' => $username,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'is_active' => true,
                ]
            );

            // Update chat info if changed
            $chat->update([
                'telegram_username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'last_message_at' => now(),
            ]);

            // Determine message type and content
            $messageType = 'text';
            $messageText = $message->getText();
            $filePath = null;

            // Check for other media types
            if ($message->getPhoto()) {
                $messageType = 'photo';
                $messageText = $message->getCaption() ?? '[Photo]';
            } elseif ($message->getDocument()) {
                $messageType = 'document';
                $messageText = $message->getCaption() ?? '[Document]';
            } elseif ($message->getVoice()) {
                $messageType = 'voice';
                $messageText = '[Voice Message]';
            } elseif ($message->getVideo()) {
                $messageType = 'video';
                $messageText = $message->getCaption() ?? '[Video]';
            } elseif ($message->getSticker()) {
                $messageType = 'sticker';
                $messageText = '[Sticker]';
            } elseif ($message->getLocation()) {
                $messageType = 'location';
                $messageText = '[Location]';
            }

            // Store the message
            $telegramMessage = TelegramMessage::create([
                'telegram_chat_id' => $chat->id,
                'message_id' => (string) $message->getMessageId(),
                'sender_type' => 'user',
                'message_text' => $messageText,
                'message_type' => $messageType,
                'file_path' => $filePath,
                'sent_at' => now(),
            ]);

            // Load relationships for broadcasting
            $telegramMessage->load('chat');

            // Broadcast event for real-time updates
            broadcast(new TelegramMessageReceived($telegramMessage))->toOthers();

            Log::info('Telegram message received', [
                'chat_id' => $chat->id,
                'message_id' => $telegramMessage->id,
                'from' => $firstName . ' ' . $lastName,
            ]);

            return response()->json(['ok' => true], 200);

        } catch (\Exception $e) {
            Log::error('Telegram webhook error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            // Always return 200 to Telegram to avoid retries
            return response()->json(['ok' => true], 200);
        }
    }
}
