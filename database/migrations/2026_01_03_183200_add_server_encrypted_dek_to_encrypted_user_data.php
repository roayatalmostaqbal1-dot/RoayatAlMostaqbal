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
            $table->text('encrypted_dek_server')->nullable()->after('encrypted_dek')
                ->comment('Data Encryption Key encrypted with server Master Key for recovery purposes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('encrypted_user_data', function (Blueprint $table) {
            $table->dropColumn('encrypted_dek_server');
        });
    }
};

