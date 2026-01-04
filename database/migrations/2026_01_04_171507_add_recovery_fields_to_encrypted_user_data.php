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
        Schema::table('encrypted_user_data', function (Blueprint $table) {
            $table->text('encrypted_dek_recovery')->nullable()->after('encrypted_dek_server')
                ->comment('Data Encryption Key encrypted with user Recovery Key');
            $table->text('dek_salt_recovery')->nullable()->after('encrypted_dek_recovery');
            $table->text('dek_nonce_recovery')->nullable()->after('dek_salt_recovery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encrypted_user_data', function (Blueprint $table) {
            $table->dropColumn(['encrypted_dek_recovery', 'dek_salt_recovery', 'dek_nonce_recovery']);
        });
    }
};
