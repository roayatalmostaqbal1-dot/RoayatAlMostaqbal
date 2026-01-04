<?php

namespace App\Http\Controllers\Api\V1\AllUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AllUser\StoreEncryptedDataRequest;
use App\Http\Resources\Api\V1\AllUser\EncryptedDataResource;
use App\Models\EncryptedUserData;
use App\Models\MasterEncryptionKey;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class EncryptedDataController extends Controller
{
    /**
     * Store encrypted user data
     */
    public function store(StoreEncryptedDataRequest $request)
    {
        try {
            $validated = $request->validated();

            // Server-side encryption if needed
            if (empty($validated['encrypted_dek_server'])) {
                $validated['encrypted_dek_server'] = $this->encryptDEKWithMasterKey($validated['encrypted_dek']);
            }

            $dataType = $validated['data_type'] ?? 'profile';

            $encryptedData = EncryptedUserData::updateOrCreate(
                ['user_id' => Auth::id(), 'data_type' => $dataType],
                $validated
            );

            return (new \App\Http\Resources\Api\V1\AllUser\EncryptedDataResource($encryptedData))
                ->additional(['success' => true, 'message' => 'Encrypted data saved successfully']);

        } catch (\Exception $e) {
            Log::error('Error storing encrypted data: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to store encrypted data'], 500);
        }
    }

    /**
     * Retrieve encrypted user data
     */
    public function show(Request $request)
    {
        try {
            $encryptedData = EncryptedUserData::forUser(Auth::id())
                ->byType($request->query('type', 'profile'))
                ->firstOrFail();

            return (new EncryptedDataResource($encryptedData))
                ->additional(['success' => true]);

        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'No encrypted data found'], 404);
        } catch (\Exception $e) {
            Log::error('Error retrieving encrypted data: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to retrieve encrypted data'], 500);
        }
    }

    /**
     * Update encrypted user data
     */
    public function update(StoreEncryptedDataRequest $request, $id)
    {
        try {
            $encryptedData = EncryptedUserData::where('user_id', Auth::id())->findOrFail($id);

            $validated = $request->validated();
            if (empty($validated['encrypted_dek_server'])) {
                $validated['encrypted_dek_server'] = $this->encryptDEKWithMasterKey($validated['encrypted_dek']);
            }

            $encryptedData->update($validated);

            return (new \App\Http\Resources\Api\V1\AllUser\EncryptedDataResource($encryptedData))
                ->additional(['success' => true, 'message' => 'Encrypted data updated successfully']);

        } catch (\Exception $e) {
            Log::error('Error updating encrypted data: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to update encrypted data'], 500);
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
            if (! $user->hasRole('super-admin') && ! $user->hasPermission('encrypted_data')) {
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
            Log::error('Error retrieving admin encrypted data: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve encrypted data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Encrypt DEK with master key (server-side)
     */
    private function encryptDEKWithMasterKey(string $encryptedDek): string
    {
        try {
            $masterKey = MasterEncryptionKey::getActiveKey();

            if (! $masterKey) {
                // If no master key exists, create one
                $masterKey = MasterEncryptionKey::generateNew();
            }
            $masterKeyValue = $masterKey->getDecryptedKey();

            return Crypt::encryptString($encryptedDek);
        } catch (\Exception $e) {
            Log::error('Error encrypting DEK with master key: '.$e->getMessage());
            throw new \RuntimeException('Failed to encrypt DEK with master key');
        }
    }
}
