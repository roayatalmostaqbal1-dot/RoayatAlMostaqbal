<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Send password reset link via email
     */
    public function sendResetLinkEmail(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $request->email)->first();

            // Only send email if user exists, but always return success message
            if ($user) {
                // Generate reset token
                $token = Str::random(64);

                // Delete old tokens for this email
                DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->delete();

                // Store new token
                DB::table('password_reset_tokens')->insert([
                    'email' => $request->email,
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]);

                // Send email with reset link
                $resetUrl = config('app.url').'/admin/reset-password/'.$token.'?email='.urlencode($request->email);

                Mail::send('emails.password-reset', [
                    'user' => $user,
                    'resetUrl' => $resetUrl,
                ], function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Reset Your Password');
                });
            }

            // Always return success message to prevent email enumeration
            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'message' => 'If an account exists with this email, you will receive a password reset link shortly.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Password Reset Email Error: '.$e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'An error occurred while processing your request',
            ], 500);
        }
    }

    /**
     * Reset password using token
     */
    public function reset(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
                'password' => [
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
                'password.min' => 'Password must be at least 8 characters',
                'password.regex' => 'Password must contain uppercase, lowercase, numbers, and special characters',
                'password.confirmed' => 'Password confirmation does not match',
            ]);

            // Find token record
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'response_code' => 400,
                    'status' => 'error',
                    'message' => 'Invalid or expired reset token',
                ], 400);
            }

            // Verify token
            if (!Hash::check($request->token, $resetRecord->token)) {
                return response()->json([
                    'response_code' => 400,
                    'status' => 'error',
                    'message' => 'Invalid or expired reset token',
                ], 400);
            }

            // Check if token is expired (60 minutes)
            $createdAt = \Carbon\Carbon::parse($resetRecord->created_at);
            if ($createdAt->addMinutes(60)->isPast()) {
                DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->delete();

                return response()->json([
                    'response_code' => 400,
                    'status' => 'error',
                    'message' => 'Reset token has expired. Please request a new one',
                ], 400);
            }

            // Update password
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'response_code' => 400,
                    'status' => 'error',
                    'message' => 'Invalid or expired reset token',
                ], 400);
            }

            $user->password = Hash::make($request->password);
            $user->password_set_at = now(); // User set their own password
            $user->save();

            // Delete used token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            event(new PasswordReset($user));

            Log::info('Password reset successful', ['user_id' => $user->id]);

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'message' => 'Password has been reset successfully',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Password Reset Error: '.$e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'An error occurred while resetting your password',
            ], 500);
        }
    }

    /**
     * Setup password for new social login users
     */
    public function setupPassword(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'password' => [
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
                'password.min' => 'Password must be at least 8 characters',
                'password.regex' => 'Password must contain uppercase, lowercase, numbers, and special characters',
                'password.confirmed' => 'Password confirmation does not match',
            ]);

            $user = User::findOrFail($request->user_id);

            // Update password and set password_set_at timestamp
            $user->password = Hash::make($request->password);
            $user->password_set_at = now();
            $user->save();

            // Create new token for the user
            $token = $user->createToken('authToken')->accessToken;

            Log::info('Password setup successful for social login user', ['user_id' => $user->id]);

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'message' => 'Password has been set successfully',
                'token' => $token,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Password Setup Error: '.$e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => 'An error occurred while setting up your password',
            ], 500);
        }
    }
}
