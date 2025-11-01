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
    Schema::create('telegram_replies', function (Blueprint $table) {
        $table->id();
        $table->foreignId('telegram_message_id')->constrained('telegram_messages')->cascadeOnDelete();
        $table->text('reply_text');
        $table->string('status')->default('pending')->index(); // pending, sent
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_replies');
    }
};
