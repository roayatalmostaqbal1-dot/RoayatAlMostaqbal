<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\{AuditLog,OAuth2Client};
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Resources\Api\V1\SuperAdmin\OAuth2Client\OAuth2ClientResource;
use App\Http\Requests\Api\V1\SuperAdmin\OAuth2Client\{StoreRequest, UpdateRequest};

class OAuth2ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = OAuth2Client::where('revoked', false)
            ->select(['id', 'name', 'secret', 'redirect_uris', 'revoked', 'created_at', 'updated_at'])
            ->orderBy('created_at', 'desc')
            ->paginate(perPage: $request->per_page ?? 10, page: $request->page ?? 1);

        return OAuth2ClientResource::collection($clients);
    }

    /**
     * Store a newly created OAuth2 Client.
     */
    public function store(StoreRequest $request)
    {
        try {
            $secret = $request->confidential ? Str::random(40) : null;

            // Ensure redirect_uris is stored as JSON array
            $redirectUris =$request->redirect;

            $client = OAuth2Client::create([
                'name' => $request->name,
                'secret' => $secret,
                'redirect_uris' => $redirectUris,
                'personal_access_client' => false,
                'password_client' => false,
                'revoked' => false,
            ]);

            // Log the action
            $clientData = $client->toArray();
            // Store redirect_uris as JSON string in audit log
            $clientData['redirect_uris'] = json_encode($client->redirect_uris);

            AuditLog::create([
                'user_id' => Auth::id(),
                'model_type' => 'OAuth2Client',
                'model_id' => $client->id,
                'action' => 'created',
                'new_values' => $clientData,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return new OAuth2ClientResource($client);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Failed to create OAuth2 Client: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified OAuth2 Client.
     */
    public function show(string $id)
    {
        $client = OAuth2Client::findOrFail($id);

        if ($client->revoked) {
            return response()->json([
                'response_code' => 404,
                'status' => 'error',
                'message' => 'OAuth2 Client not found',
            ], 404);
        }

        return new OAuth2ClientResource($client);
    }

    /**
     * Update the specified OAuth2 Client.
     */
    public function update(UpdateRequest $request, string $id)
    {
        try {
            $client = OAuth2Client::findOrFail($id);

            if ($client->revoked) {
                return response()->json([
                    'response_code' => 404,
                    'status' => 'error',
                    'message' => 'OAuth2 Client not found',
                ], 404);
            }

            $oldValues = $client->toArray();
            // Store redirect_uris as JSON string in audit log
            $oldValues['redirect_uris'] = json_encode($client->redirect_uris);

            // Ensure redirect_uris is stored as JSON array
            $redirectUris =$request->redirect;


            $client->update([
                'name' => $request->name,
                'redirect_uris' => $redirectUris,
            ]);

            // Log the action
            $newValues = $client->toArray();
            // Store redirect_uris as JSON string in audit log
            $newValues['redirect_uris'] = json_encode($client->redirect_uris);

            AuditLog::create([
                'user_id' => Auth::id(),
                'model_type' => 'OAuth2Client',
                'model_id' => $client->id,
                'action' => 'updated',
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return new OAuth2ClientResource($client);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Failed to update OAuth2 Client: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete the specified OAuth2 Client.
     */
    public function destroy(string $id, Request $request)
    {
        try {
            $client = OAuth2Client::findOrFail($id);

            if ($client->revoked) {
                return response()->json([
                    'response_code' => 404,
                    'status' => 'error',
                    'message' => 'OAuth2 Client not found',
                ], 404);
            }

            $clientData = $client->toArray();
            // Store redirect_uris as JSON string in audit log
            $clientData['redirect_uris'] = json_encode($client->redirect_uris);

            $client->update(['revoked' => true]);

            // Log the action
            AuditLog::create([
                'user_id' => Auth::id(),
                'model_type' => 'OAuth2Client',
                'model_id' => $client->id,
                'action' => 'deleted',
                'old_values' => $clientData,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'message' => 'OAuth2 Client deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Failed to delete OAuth2 Client: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Regenerate the client secret.
     */
    public function regenerateSecret(string $id, Request $request)
    {
        try {
            $client = OAuth2Client::findOrFail($id);

            if ($client->revoked) {
                return response()->json([
                    'response_code' => 404,
                    'status' => 'error',
                    'message' => 'OAuth2 Client not found',
                ], 404);
            }

            if (is_null($client->secret)) {
                return response()->json([
                    'response_code' => 400,
                    'status' => 'error',
                    'message' => 'Cannot regenerate secret for public clients',
                ], 400);
            }

            $oldValues = $client->toArray();
            // Store redirect_uris as JSON string in audit log
            $oldValues['redirect_uris'] = json_encode($client->redirect_uris);

            $newSecret = Str::random(40);

            $client->update(['secret' => $newSecret]);

            // Log the action
            $newValues = $client->toArray();
            // Store redirect_uris as JSON string in audit log
            $newValues['redirect_uris'] = json_encode($client->redirect_uris);

            AuditLog::create([
                'user_id' => Auth::id(),
                'model_type' => 'OAuth2Client',
                'model_id' => $client->id,
                'action' => 'regenerated_secret',
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return new OAuth2ClientResource($client);
        } catch (\Exception $e) {
            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Failed to regenerate secret: ' . $e->getMessage(),
            ], 500);
        }
    }
}

