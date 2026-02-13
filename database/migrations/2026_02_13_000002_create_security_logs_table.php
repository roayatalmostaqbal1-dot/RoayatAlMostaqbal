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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->enum('event_type', [
                'login_success',
                'login_failed',
                'logout',
                'access_attempt',
                'system_scan',
                'data_access',
                'data_export',
                'malware_alert',
                'unauthorized_access',
                'password_change',
                'settings_change',
                'api_request',
                'file_upload',
                'file_download',
            ]);
            $table->string('source'); // IP address or system identifier
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('low');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('user_id');
            $table->index('event_type');
            $table->index('severity');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
