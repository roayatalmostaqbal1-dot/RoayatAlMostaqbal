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
        Schema::create('master_encryption_keys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key_id')->unique()->comment('Unique identifier for the key version');
            $table->text('encrypted_key')->comment('Master key encrypted with APP_KEY');
            $table->text('public_key')->nullable()->comment('Public key for encrypting DEK (if using asymmetric)');
            $table->boolean('is_active')->default(true)->comment('Whether this key is currently active');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('is_active');
            $table->index('key_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_encryption_keys');
    }
};

