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
        Schema::create('user_dashboard_data', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->enum('digital_identity_status', ['active', 'inactive'])->default('inactive');
            $table->string('identity_number')->nullable();
            $table->string('nationality')->nullable();
            $table->enum('security_level', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->boolean('uae_pass_connected')->default(false);
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();

            // Ensure one record per user
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_dashboard_data');
    }
};
