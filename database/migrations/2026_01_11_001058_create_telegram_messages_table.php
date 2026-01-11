<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('telegram_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('telegram_chat_id')->constrained('telegram_chats')->onDelete('cascade');
            $table->string('message_id')->index(); // Telegram message ID
            $table->enum('sender_type', ['user', 'admin'])->index();
            $table->foreignUuid('admin_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('message_text')->nullable();
            $table->enum('message_type', ['text', 'photo', 'document', 'voice', 'video', 'sticker', 'location'])->default('text');
            $table->string('file_path')->nullable();
            $table->boolean('is_read')->default(false)->index();
            $table->timestamp('sent_at')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_messages');
    }
};
