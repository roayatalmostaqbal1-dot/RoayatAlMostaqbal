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
        Schema::table('oauth_clients', function (Blueprint $table) {
            // Add missing columns that Laravel Passport expects
            if (!Schema::hasColumn('oauth_clients', 'personal_access_client')) {
                $table->boolean('personal_access_client')->default(false)->after('redirect_uris');
            }
            
            if (!Schema::hasColumn('oauth_clients', 'password_client')) {
                $table->boolean('password_client')->default(false)->after('personal_access_client');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('oauth_clients', function (Blueprint $table) {
            if (Schema::hasColumn('oauth_clients', 'personal_access_client')) {
                $table->dropColumn('personal_access_client');
            }
            
            if (Schema::hasColumn('oauth_clients', 'password_client')) {
                $table->dropColumn('password_client');
            }
        });
    }

    /**
     * Get the migration connection name.
     */
    public function getConnection(): ?string
    {
        return $this->connection ?? config('passport.connection');
    }
};

