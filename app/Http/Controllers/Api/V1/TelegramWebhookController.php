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
        Log::info('--- TELEGRAM WEBHOOK RECEIVED ---');
        Log::info('IP: '.$request->ip());
        Log::info('Body: '.json_encode($request->all()));

        try {
            // Get the update from Telegram
            $update = Telegram::bot()->commandsHandler(true);

            Log::debug('Telegram Update Parsed', [
                'update_id' => $update->getUpdateId(),
                'has_message' => (bool) $update->getMessage(),
            ]);

            // Check if it's a message
            if (! $update->getMessage()) {
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
            $updateData = [
                'telegram_username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'last_message_at' => now(),
            ];

            // Fetch profile photo if not exists
            if (empty($chat->photo_url)) {
                try {
                    $photos = Telegram::bot()->getUserProfilePhotos(['user_id' => $telegramUserId, 'limit' => 1]);
                    if ($photos && $photos->getTotalCount() > 0) {
                        $photo = $photos->getPhotos()[0][0];
                        $fileId = $photo['file_id'];
                        $file = Telegram::bot()->getFile(['file_id' => $fileId]);
                        $filePath = $file->getFilePath();
                        $updateData['photo_url'] = 'https://api.telegram.org/file/bot'.config('telegram.bots.mybot.token').'/'.$filePath;
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to fetch Telegram profile photo: '.$e->getMessage());
                }
            }

            $chat->update($updateData);

            // Determine message type and content
            $messageType = 'text';
            $messageText = $message->getText();
            $fileId = null;

            // Check for other media types
            if ($message->getPhoto() && count($message->getPhoto()) > 0) {
                $messageType = 'photo';
                $messageText = $message->getCaption() ?? '[Photo]';
                $photo = collect($message->getPhoto())->last();
                $fileId = $photo['file_id'] ?? null;
            } elseif ($message->getDocument()) {
                $messageType = 'document';
                $messageText = $message->getCaption() ?? '[Document]';
                $fileId = $message->getDocument()->getFileId();
            } elseif ($message->getVoice()) {
                $messageType = 'voice';
                $messageText = '[Voice Message]';
                $fileId = $message->getVoice()->getFileId();
            } elseif ($message->getVideo()) {
                $messageType = 'video';
                $messageText = $message->getCaption() ?? '[Video]';
                $fileId = $message->getVideo()->getFileId();
            } elseif ($message->getSticker()) {
                $messageType = 'sticker';
                $messageText = '[Sticker]';
                $fileId = $message->getSticker()->getFileId();
            } elseif ($message->getLocation()) {
                $messageType = 'location';
                $messageText = '[Location]';
            }

            // Fetch file path if fileId is present
            $filePath = null;
            if ($fileId) {
                try {
                    $file = Telegram::bot()->getFile(['file_id' => $fileId]);
                    $tgFilePath = $file->getFilePath();
                    $filePath = 'https://api.telegram.org/file/bot'.config('telegram.bots.mybot.token').'/'.$tgFilePath;
                } catch (\Exception $e) {
                    Log::warning('Failed to fetch Telegram file path: '.$e->getMessage());
                }
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

            // Load relationships for broadcasting and notification
            $telegramMessage->load('chat');

            // Notify admins
            \App\Services\NotificationService::notifyAdmins(new \App\Notifications\TelegramMessageNotification($telegramMessage));

            Log::info('Telegram message details', [
                'type' => $messageType,
                'file_id' => $fileId,
                'file_path' => $filePath,
                'token_exists' => (bool) config('telegram.bots.mybot.token'),
            ]);

            // Broadcast event for real-time updates
            broadcast(new TelegramMessageReceived($telegramMessage))->toOthers();

            Log::info('Telegram message received', [
                'chat_id' => $chat->id,
                'message_id' => $telegramMessage->id,
                'from' => $firstName.' '.$lastName,
            ]);

            return response()->json(['ok' => true], 200);

        } catch (\Exception $e) {
            Log::error('Telegram webhook error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            // Always return 200 to Telegram to avoid retries
            return response()->json(['ok' => true], 200);
        }
    }
}
