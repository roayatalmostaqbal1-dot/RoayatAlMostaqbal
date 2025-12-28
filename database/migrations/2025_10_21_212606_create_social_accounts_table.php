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
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('provider_name');
            $table->string('provider_id')->unique();
            $table->string('provider_token')->nullable();
            $table->timestamps();

            $table->index(['user_id']);

            // Use explicit constraint name to avoid orphan FK conflicts
            $table->foreign('user_id', 'fk_social_accounts_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
