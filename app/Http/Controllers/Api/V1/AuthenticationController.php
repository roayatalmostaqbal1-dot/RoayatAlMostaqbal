<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\User\UserInfoResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash, Log};


class AuthenticationController extends Controller
{
    /**
     * Register a new account.
     */
    public function register(RegisterRequest $request)
    {

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Assign default role if needed
            // $user->assignRole('viewer');
            $user->save();

            // Create Passport token for the new user
            $token = $user->createToken('authToken')->accessToken;

            $response = (new UserInfoResource($user))
                ->additional(['token' => $token])
                ->toArray($request);

            // Add token to response
            $response['token'] = $token;

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Registration Error: '.$e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login request.
     */
   public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'response_code' => 401,
                    'status' => 'error',
                    'message' => 'Unauthorized',
                ], 401);
            }
            if ($user->hasTwoFactorEnabled()) {
                return response()->json([
                    'response_code' => 200,
                    'status' => 'success',
                    'message' => '2FA required',
                    'data' => [
                        'two_factor_enabled' => true,
                        'user_id' => $user->id,
                    ],
                ]);
            }
             $token = $user->createToken('authToken')->accessToken;
            $response = (new UserInfoResource($user))
                ->additional(['token' => $token])
                ->toArray($request);

            // Add token to response
            $response['token'] = $token;

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Login Error: '.$e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function logOut(Request $request)
    {
        try {
            if (Auth::check()) {
                Auth::user()->tokens()->delete();

                return response()->json([
                    'response_code' => 200,
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ]);
            }

            return response()->json([
                'response_code' => 401,
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        } catch (\Exception $e) {
            Log::error('Logout Error: '.$e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'An error occurred during logout',
            ], 500);
        }
    }

     public function changePassword(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'current_password' => 'required|string',
                'new_password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[a-z]/',
                    'regex:/[A-Z]/',
                    'regex:/[0-9]/',
                    'regex:/[^a-zA-Z0-9]/',
                    'confirmed',
                ],
            ], [
                'new_password.min' => 'Password must be at least 8 characters',
                'new_password.regex' => 'Password must contain uppercase, lowercase, numbers, and special characters',
                'new_password.confirmed' => 'Password confirmation does not match',
            ]);

            $user = Auth::user();

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'response_code' => 422,
                    'status' => 'error',
                    'message' => 'Current password is incorrect',
                    'errors' => [
                        'current_password' => ['Current password is incorrect'],
                    ],
                ], 422);
            }

            // Check if new password is same as current
            if (Hash::check($request->new_password, $user->password)) {
                return response()->json([
                    'response_code' => 422,
                    'status' => 'error',
                    'message' => 'New password cannot be the same as current password',
                    'errors' => [
                        'new_password' => ['New password cannot be the same as current password'],
                    ],
                ], 422);
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            Log::info('User password changed', ['user_id' => $user->id]);

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'message' => 'Password changed successfully',
                'success' => true,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Change Password Error: '.$e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'An error occurred while changing password',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
