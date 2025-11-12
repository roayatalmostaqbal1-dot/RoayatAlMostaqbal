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
        Schema::create('encrypted_user_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Encryption-related fields
            // These fields store the encrypted Data Encryption Key (DEK)
            $table->text('encrypted_dek')->comment('Data Encryption Key encrypted with Key Encryption Key (KEK)');
            $table->string('dek_salt', 255)->comment('Salt used in Key Derivation Function (KDF) when encrypting DEK');
            $table->string('dek_nonce', 255)->comment('Nonce used when encrypting DEK with XChaCha20-Poly1305');
            
            // Encrypted sensitive data
            // These fields store the actual encrypted user data
            $table->text('profile_ciphertext')->comment('Encrypted sensitive data (e.g., user profile information)');
            $table->string('profile_nonce', 255)->comment('Nonce for decrypting the profile data with XChaCha20-Poly1305');
            
            // Metadata
            $table->string('data_type', 100)->default('profile')->comment('Type of encrypted data (profile, settings, etc.)');
            $table->text('metadata')->nullable()->comment('Additional metadata about the encrypted data (not encrypted)');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('data_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encrypted_user_data');
    }
};

