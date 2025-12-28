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
        Schema::create('role_page', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('role_id'); // Spatie roles use bigIncrements
            $table->string('page_key');
            $table->timestamps();

            // Foreign key for role (Spatie roles table uses bigIncrements)
            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            // Unique constraint to prevent duplicate assignments
            $table->unique(['role_id', 'page_key']);

            // Indexes for faster queries
            $table->index('role_id');
            $table->index('page_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_page');
    }
};

