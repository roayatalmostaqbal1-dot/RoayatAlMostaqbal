<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Migrate existing 2FA data from users table to user_two_factor_auth table
     */
    public function up(): void
    {
        // Check if 2FA columns still exist in users table
        if (!Schema::hasColumn('users', 'two_factor_secret') && !Schema::hasColumn('users', 'two_factor_enabled')) {
            // Columns already dropped, create default records for all users
            $users = DB::table('users')->get();
            foreach ($users as $user) {
                // Check if record already exists
                $exists = DB::table('user_two_factor_auth')->where('user_id', $user->id)->exists();
                if (!$exists) {
                    DB::table('user_two_factor_auth')->insert([
                        'user_id' => $user->id,
                        'two_factor_enabled' => false,
                        'two_factor_secret' => null,
                        'two_factor_recovery_codes' => null,
                        'two_factor_confirmed_at' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            return;
        }

        // Get all users with 2FA enabled
        $users = DB::table('users')
            ->whereNotNull('two_factor_secret')
            ->orWhere('two_factor_enabled', true)
            ->get();

        foreach ($users as $user) {
            // Insert into user_two_factor_auth table
            DB::table('user_two_factor_auth')->insert([
                'user_id' => $user->id,
                'two_factor_enabled' => $user->two_factor_enabled ?? false,
                'two_factor_secret' => $user->two_factor_secret,
                'two_factor_recovery_codes' => $user->two_factor_recovery_codes,
                'two_factor_confirmed_at' => $user->two_factor_enabled ? now() : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // For users without 2FA data, create empty records
        $usersWithout2FA = DB::table('users')
            ->whereNull('two_factor_secret')
            ->where('two_factor_enabled', false)
            ->get();

        foreach ($usersWithout2FA as $user) {
            DB::table('user_two_factor_auth')->insert([
                'user_id' => $user->id,
                'two_factor_enabled' => false,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is one-way - data migration cannot be safely reversed
        // If you need to rollback, manually restore from backup
    }
};

