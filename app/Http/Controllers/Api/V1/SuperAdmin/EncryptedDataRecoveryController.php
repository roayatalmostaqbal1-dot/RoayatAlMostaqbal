<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SuperAdmin\RecoverDataRequest;
use App\Http\Resources\Api\V1\SuperAdmin\RecoveredDataResource;
use App\Models\{EncryptedUserData,MasterEncryptionKey};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\{Log,Auth,Crypt};


class EncryptedDataRecoveryController extends Controller
{
    /**
     * Get master key public key for client-side encryption
     * GET /api/v1/master-key/public-key
     */
    public function getMasterKeyPublicKey()
    {
        try {
            $masterKey = MasterEncryptionKey::getActiveKey();

            if (!$masterKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active master key found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'server_side_encryption_available' => true,
                'key_id' => $masterKey->key_id,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving master key: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve master key',
            ], 500);
        }
    }

    /**
     * Recover encrypted data for a user (Admin only)
     */
    public function recoverData(RecoverDataRequest $request, $userId)
    {
        try {
            $dataType = $request->query('type', 'profile');

            // Log attempt
            Log::info('Data recovery attempt', [
                'admin_id' => Auth::id(),
                'user_id' => $userId,
                'requested_type' => $dataType,
            ]);

            $encryptedData = EncryptedUserData::forUser($userId)
                ->byType($dataType)
                ->firstOrFail();

            // Check if this data was encrypted with the DEK method
            if (empty($encryptedData->dek_salt) || empty($encryptedData->dek_nonce) || empty($encryptedData->encrypted_dek)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This data was encrypted using an incompatible method and cannot be recovered with this tool.',
                ], 400);
            }

            // Use encrypted_dek_server if available, otherwise fallback to encrypted_dek
            $encryptedDekToUse = $encryptedData->encrypted_dek_server ?? $encryptedData->encrypted_dek;

            // Decrypt the encrypted DEK using master key (if it was encrypted with master key)
            try {
                $encryptedDek = $this->decryptDEKWithMasterKey($encryptedDekToUse);
            } catch (\Exception $e) {
                // If decryption fails, assume it was not encrypted with master key (standard dek)
                $encryptedDek = $encryptedData->encrypted_dek;
            }

            // Decrypt DEK using user's password
            $dek = $this->decryptDEKWithPassword(
                $encryptedDek,
                $encryptedData->dek_salt,
                $encryptedData->dek_nonce,
                $request->user_password
            );

            // Decrypt the actual data
            $decryptedData = $this->decryptDataWithDEK(
                $encryptedData->profile_ciphertext,
                $encryptedData->profile_nonce,
                $dek
            );

            // Log the recovery action
            Log::warning('Data recovery performed by admin', [
                'admin_id' => Auth::id(),
                'user_id' => $userId,
                'data_type' => $dataType,
                'ip_address' => $request->ip(),
            ]);

            return (new RecoveredDataResource([
                'decrypted_data' => json_decode($decryptedData, true),
                'recovered_at' => now()->toIso8601String(),
                'recovered_by' => Auth::id(),
                'data_type' => $dataType,
                'user_id' => $userId,
            ]))->additional(['success' => true]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => "No encrypted data found for type '{$dataType}'"], 404);
        } catch (\Exception $e) {
            Log::error('Error recovering encrypted data: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to recover data: '.$e->getMessage()], 500);
        }
    }

    /**
     * Decrypt DEK with master key
     */
    private function decryptDEKWithMasterKey(string $encryptedDekServer): string
    {
        return Crypt::decryptString($encryptedDekServer);
    }

    /**
     * Decrypt DEK with user's password (requires sodium extension)
     */
    private function decryptDEKWithPassword(string $encryptedDek, string $dekSalt, string $dekNonce, string $password): string
    {
        if (!function_exists('sodium_crypto_pwhash')) {
            throw new \RuntimeException('Sodium extension not available');
        }

        // Convert URL-safe base64 to standard base64
        $dekSalt = str_replace(['-', '_'], ['+', '/'], $dekSalt);
        $encryptedDek = str_replace(['-', '_'], ['+', '/'], $encryptedDek);
        $dekNonce = str_replace(['-', '_'], ['+', '/'], $dekNonce);

        $saltBytes = base64_decode($dekSalt);
        $encryptedDekBytes = base64_decode($encryptedDek);
        $nonceBytes = base64_decode($dekNonce);

        if (strlen($saltBytes) !== SODIUM_CRYPTO_PWHASH_SALTBYTES) {
            throw new \RuntimeException('Invalid salt length');
        }

        if (strlen($nonceBytes) !== SODIUM_CRYPTO_AEAD_XCHACHA20POLY1305_IETF_NPUBBYTES) {
            throw new \RuntimeException('Invalid nonce length');
        }

        // Derive KEK
        $kek = sodium_crypto_pwhash(
            32,
            $password,
            $saltBytes,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_MODERATE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_MODERATE,
            SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13
        );

        // Decrypt DEK
        $dek = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt($encryptedDekBytes, '', $nonceBytes, $kek);

        if ($dek === false) {
            throw new \RuntimeException('Wrong password or corrupted data');
        }

        return base64_encode($dek);
    }

    /**
     * Decrypt data with DEK
     */
    private function decryptDataWithDEK(string $ciphertext, string $nonce, string $dek): string
    {
        $ciphertext = str_replace(['-', '_'], ['+', '/'], $ciphertext);
        $nonce = str_replace(['-', '_'], ['+', '/'], $nonce);
        $dek = str_replace(['-', '_'], ['+', '/'], $dek);

        $plaintext = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
            base64_decode($ciphertext),
            '',
            base64_decode($nonce),
            base64_decode($dek)
        );

        if ($plaintext === false) {
            throw new \RuntimeException('Decryption failed');
        }

        return $plaintext;
    }
}
