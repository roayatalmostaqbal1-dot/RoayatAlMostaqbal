<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Events\TelegramMessageSent;
use App\Http\Controllers\Controller;
use App\Models\TelegramChat;
use App\Models\TelegramMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramChatController extends Controller
{
    /**
     * List all telegram chats with pagination and search
     */
    public function index(Request $request)
    {
        $query = TelegramChat::query()
            ->with(['latestMessage', 'unreadMessages'])
            ->active();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        // Order by last message time
        $query->orderBy('last_message_at', 'desc');

        $chats = $query->paginate($request->per_page ?? 20);

        // Add unread count to each chat
        $chats->getCollection()->transform(function ($chat) {
            $chat->unread_count = $chat->unreadMessages()->count();
            return $chat;
        });

        return response()->json([
            'success' => true,
            'data' => $chats,
        ]);
    }

    /**
     * Get specific chat with message history
     */
    public function show(Request $request, TelegramChat $chat)
    {
        $messages = $chat->messages()
            ->with(['admin', 'chat'])
            ->orderBy('sent_at', 'desc')
            ->paginate($request->per_page ?? 50);

        return response()->json([
            'success' => true,
            'data' => [
                'chat' => $chat,
                'messages' => $messages,
            ],
        ]);
    }

    /**
     * Send message to Telegram user
     */
    public function sendMessage(Request $request, TelegramChat $chat)
    {
        $request->validate([
            'message' => 'required|string|max:4096',
        ]);

        try {
            // Send message via Telegram Bot API
            $response = Telegram::bot()->sendMessage([
                'chat_id' => $chat->telegram_user_id,
                'text' => $request->message,
                'parse_mode' => 'HTML',
            ]);

            // Store message in database
            $message = TelegramMessage::create([
                'telegram_chat_id' => $chat->id,
                'message_id' => (string) $response->getMessageId(),
                'sender_type' => 'admin',
                'admin_user_id' => Auth::id(),
                'message_text' => $request->message,
                'message_type' => 'text',
                'is_read' => true, // Admin messages are marked as read
                'sent_at' => now(),
            ]);

            // Update chat last message time
            $chat->update([
                'last_message_at' => now(),
            ]);

            // Load admin for broadcasting
            $message->load('admin');

            // Broadcast event for real-time updates
            broadcast(new TelegramMessageSent($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $message->load('admin'),
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending Telegram message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to send message: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(TelegramChat $chat)
    {
        $chat->messages()
            ->where('sender_type', 'user')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Messages marked as read',
        ]);
    }
}
