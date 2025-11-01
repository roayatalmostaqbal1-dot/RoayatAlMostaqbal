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
            $table->bigInteger('chat_id')->index();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->text('message_text');
            $table->boolean('is_from_user')->default(true);
            $table->string('status')->default('pending')->index(); // pending, processed
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
