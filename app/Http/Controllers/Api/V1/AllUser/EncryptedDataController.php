<?php

namespace App\Http\Controllers\Api\V1\AllUser;

use App\Http\Controllers\Controller;
use App\Models\EncryptedUserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EncryptedDataController extends Controller
{
    /**
     * Store encrypted user data
     * POST /api/v1/encrypted-data
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            // Validate encrypted data structure
            $validated = $request->validate([
                'encrypted_dek' => 'required|string',
                'dek_salt' => 'required|string',
                'dek_nonce' => 'required|string',
                'profile_ciphertext' => 'required|string',
                'profile_nonce' => 'required|string',
                'data_type' => 'nullable|string|max:100',
                'metadata' => 'nullable|string',
            ]);

            // Check if user already has encrypted data of this type
            $dataType = $validated['data_type'] ?? 'profile';
            $existingData = EncryptedUserData::forUser($user->id)
                ->byType($dataType)
                ->first();

            if ($existingData) {
                // Update existing record
                $existingData->update([
                    'encrypted_dek' => $validated['encrypted_dek'],
                    'dek_salt' => $validated['dek_salt'],
                    'dek_nonce' => $validated['dek_nonce'],
                    'profile_ciphertext' => $validated['profile_ciphertext'],
                    'profile_nonce' => $validated['profile_nonce'],
                    'metadata' => $validated['metadata'] ?? null,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Encrypted data updated successfully',
                    'data' => $existingData,
                ], 200);
            }

            // Create new encrypted data record
            $encryptedData = EncryptedUserData::create([
                'user_id' => $user->id,
                'encrypted_dek' => $validated['encrypted_dek'],
                'dek_salt' => $validated['dek_salt'],
                'dek_nonce' => $validated['dek_nonce'],
                'profile_ciphertext' => $validated['profile_ciphertext'],
                'profile_nonce' => $validated['profile_nonce'],
                'data_type' => $dataType,
                'metadata' => $validated['metadata'] ?? null,
            ]);

            Log::info("Encrypted data stored for user {$user->id}", [
                'data_type' => $dataType,
                'id' => $encryptedData->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Encrypted data stored successfully',
                'data' => $encryptedData,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error storing encrypted data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to store encrypted data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Retrieve encrypted user data
     * GET /api/v1/encrypted-data
     */
    public function show(Request $request)
    {
        try {
            $user = Auth::user();
            $dataType = $request->query('type', 'profile');

            $encryptedData = EncryptedUserData::forUser($user->id)
                ->byType($dataType)
                ->first();

            if (!$encryptedData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No encrypted data found',
                ], 404);
            }

            // Return encrypted data (NOT decrypted - decryption happens client-side)
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $encryptedData->id,
                    'encrypted_dek' => $encryptedData->encrypted_dek,
                    'dek_salt' => $encryptedData->dek_salt,
                    'dek_nonce' => $encryptedData->dek_nonce,
                    'profile_ciphertext' => $encryptedData->profile_ciphertext,
                    'profile_nonce' => $encryptedData->profile_nonce,
                    'data_type' => $encryptedData->data_type,
                    'created_at' => $encryptedData->created_at,
                    'updated_at' => $encryptedData->updated_at,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving encrypted data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve encrypted data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update encrypted user data
     * PUT /api/v1/encrypted-data/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Auth::user();

            $encryptedData = EncryptedUserData::findOrFail($id);

            // Verify ownership
            if ($encryptedData->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            // Validate encrypted data structure
            $validated = $request->validate([
                'encrypted_dek' => 'required|string',
                'dek_salt' => 'required|string',
                'dek_nonce' => 'required|string',
                'profile_ciphertext' => 'required|string',
                'profile_nonce' => 'required|string',
                'metadata' => 'nullable|string',
            ]);

            $encryptedData->update($validated);

            Log::info("Encrypted data updated for user {$user->id}", [
                'id' => $encryptedData->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Encrypted data updated successfully',
                'data' => $encryptedData,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating encrypted data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update encrypted data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get encrypted data for admin/debug purposes
     * GET /api/v1/admin/encrypted-data/{userId}
     * Only accessible to admin users
     */
    public function adminShow(Request $request, $userId)
    {
        try {
            $user = Auth::user();

            // Check if user is admin
            if (!$user->hasRole('super-admin') && !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Admin access required',
                ], 403);
            }

            $encryptedData = EncryptedUserData::forUser($userId)->get();

            if ($encryptedData->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No encrypted data found for this user',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $encryptedData,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving admin encrypted data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve encrypted data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

