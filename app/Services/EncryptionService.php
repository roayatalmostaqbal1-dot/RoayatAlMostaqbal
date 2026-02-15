<?php

namespace App\Services;

use App\Models\EncryptedUserData;
use Illuminate\Support\Facades\Log;

/**
 * EncryptionService
 *
 * Handles encryption and decryption of sensitive data using the hybrid encryption model.
 * This service works with data that has already been encrypted on the client-side.
 *
 * Encryption Flow:
 * 1. Client generates DEK (Data Encryption Key)
 * 2. Client encrypts DEK with KEK (derived from password)
 * 3. Client encrypts sensitive data with DEK
 * 4. Client sends encrypted data to server (DEK is NOT sent)
 * 5. Server stores encrypted data as-is
 *
 * For Patent Documentation:
 * - This implements Client-Side Encryption (CSE)
 * - DEK is never transmitted to the server
 * - Server stores only encrypted payloads
 * - Decryption happens only on client-side with user's password
 */
class EncryptionService
{
    /**
     * Get encrypted data for a user (raw encrypted format)
     *
     * Returns the data exactly as stored in the database - fully encrypted.
     * This is used for demo purposes to show that data is stored encrypted.
     */
    public function getEncryptedDataRaw(string $userId, string $dataType = 'profile'): ?array
    {
        try {
            $encryptedData = EncryptedUserData::forUser($userId)
                ->byType($dataType)
                ->first();

            if (! $encryptedData) {
                return null;
            }

            // Return the raw encrypted data exactly as stored
            return [
                'id' => $encryptedData->id,
                'user_id' => $encryptedData->user_id,
                'data_type' => $encryptedData->data_type,
                'encrypted_dek' => $encryptedData->encrypted_dek,
                'dek_salt' => $encryptedData->dek_salt,
                'dek_nonce' => $encryptedData->dek_nonce,
                'profile_ciphertext' => $encryptedData->profile_ciphertext,
                'profile_nonce' => $encryptedData->profile_nonce,
                'encrypted_dek_server' => $encryptedData->encrypted_dek_server,
                'encrypted_dek_recovery' => $encryptedData->encrypted_dek_recovery,
                'dek_salt_recovery' => $encryptedData->dek_salt_recovery,
                'dek_nonce_recovery' => $encryptedData->dek_nonce_recovery,
                'metadata' => $encryptedData->metadata,
                'created_at' => $encryptedData->created_at,
                'updated_at' => $encryptedData->updated_at,
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving encrypted data: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get all encrypted data for a user
     */
    public function getAllEncryptedDataRaw(string $userId): array
    {
        try {
            $encryptedDataList = EncryptedUserData::forUser($userId)->get();

            return $encryptedDataList->map(function ($data) {
                return [
                    'id' => $data->id,
                    'user_id' => $data->user_id,
                    'data_type' => $data->data_type,
                    'encrypted_dek' => $data->encrypted_dek,
                    'dek_salt' => $data->dek_salt,
                    'dek_nonce' => $data->dek_nonce,
                    'profile_ciphertext' => $data->profile_ciphertext,
                    'profile_nonce' => $data->profile_nonce,
                    'encrypted_dek_server' => $data->encrypted_dek_server,
                    'encrypted_dek_recovery' => $data->encrypted_dek_recovery,
                    'dek_salt_recovery' => $data->dek_salt_recovery,
                    'dek_nonce_recovery' => $data->dek_nonce_recovery,
                    'metadata' => $data->metadata,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error retrieving all encrypted data: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Verify that data is encrypted
     */
    public function isDataEncrypted(string $userId, string $dataType = 'profile'): bool
    {
        try {
            $encryptedData = EncryptedUserData::forUser($userId)
                ->byType($dataType)
                ->first();

            if (! $encryptedData) {
                return false;
            }

            return ! empty($encryptedData->encrypted_dek) &&
                   ! empty($encryptedData->dek_salt) &&
                   ! empty($encryptedData->dek_nonce) &&
                   ! empty($encryptedData->profile_ciphertext) &&
                   ! empty($encryptedData->profile_nonce);
        } catch (\Exception $e) {
            Log::error('Error checking encryption status: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Get encryption metadata for a user's data
     */
    public function getEncryptionMetadata(string $userId, string $dataType = 'profile'): ?array
    {
        try {
            $encryptedData = EncryptedUserData::forUser($userId)
                ->byType($dataType)
                ->first();

            if (! $encryptedData) {
                return null;
            }

            return [
                'is_encrypted' => true,
                'encryption_method' => 'XChaCha20-Poly1305',
                'key_derivation' => 'HKDF-SHA256',
                'has_dek_encryption' => ! empty($encryptedData->encrypted_dek),
                'has_recovery_encryption' => ! empty($encryptedData->encrypted_dek_recovery),
                'has_server_encryption' => ! empty($encryptedData->encrypted_dek_server),
                'created_at' => $encryptedData->created_at,
                'updated_at' => $encryptedData->updated_at,
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving encryption metadata: '.$e->getMessage());

            return null;
        }
    }
}
