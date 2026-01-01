<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds government compliance fields for privacy consent tracking
     * and audit trail requirements.
     */
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Privacy consent timestamp for GDPR/UAE data protection compliance
            $table->timestamp('privacy_consent_at')->nullable()->after('message');
            // IP address for audit trail (government compliance requirement)
            $table->string('ip_address', 45)->nullable()->after('privacy_consent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['privacy_consent_at', 'ip_address']);
        });
    }
};
