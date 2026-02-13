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
        Schema::create('ai_insights', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('risk_level', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->integer('risk_score')->default(0); // 0-100
            $table->text('recommendation');
            $table->json('threat_indicators')->nullable();
            $table->timestamp('generated_at');
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('risk_level');
            $table->index('generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_insights');
    }
};
