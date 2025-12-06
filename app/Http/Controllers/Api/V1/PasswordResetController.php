<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\{ResetPasswordRequest,SendPasswordResetLinkRequest,SetupPasswordRequest};
use App\Models\{User,PasswordResetToken};
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Send password reset link via email
     */
    public function sendResetLinkEmail(SendPasswordResetLinkRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            // Only send email if user exists, but always return success message
            if ($user) {
                // Generate reset token
                $token = Str::random(64);

                // Delete old tokens for this email
                PasswordResetToken::forEmail($request->email)->delete();

                // Store new token using ORM
                PasswordResetToken::create([
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
    public function reset(ResetPasswordRequest $request)
    {
        try {
            // Find token record using ORM
            $resetRecord = PasswordResetToken::forEmail($request->email)->first();

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
            if ($resetRecord->isExpired()) {
                $resetRecord->delete();

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
            $resetRecord->delete();

            event(new PasswordReset($user));

            Log::info('Password reset successful', ['user_id' => $user->id]);

            return response()->json([
                'response_code' => 200,
                'status' => 'success',
                'message' => 'Password has been reset successfully',
            ]);

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
    public function setupPassword(SetupPasswordRequest $request)
    {
        try {
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
