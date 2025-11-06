<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthController extends Controller
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate QR code and secret for 2FA setup
     */
    public function enable(Request $request)
    {
        try {
            $user = Auth::user();

            // Generate secret key
            $secret = $this->google2fa->generateSecretKey();

            // Generate QR code
            $qrCode = $this->google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                $secret
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'secret' => $secret,
                    'qr_code' => $qrCode,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate 2FA setup',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Confirm 2FA setup with TOTP code
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'secret' => 'required|string',
            'code' => 'required|string|size:6',
        ]);

        try {
            $user = Auth::user();

            // Verify the TOTP code
            if (!$this->google2fa->verifyKey($request->secret, $request->code)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid verification code',
                ], 422);
            }

            // Generate recovery codes
            $recoveryCodes = $this->generateRandomCodes();

            // Save 2FA settings
            $user->update([
                'two_factor_enabled' => true,
                'two_factor_secret' => encrypt($request->secret),
                'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            ]);

            return response()->json([
                'success' => true,
                'message' => '2FA has been enabled successfully',
                'data' => [
                    'recovery_codes' => $recoveryCodes,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm 2FA',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Disable 2FA after password confirmation
     */
    public function disable(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        try {
            $user = Auth::user();

            // Verify password
            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password',
                ], 422);
            }

            // Disable 2FA
            $user->update([
                'two_factor_enabled' => false,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => '2FA has been disabled successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to disable 2FA',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify TOTP code during login
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'user_id' => 'required|integer',
        ]);

        try {
            $user = \App\Models\User::findOrFail($request->user_id);

            if (!$user->two_factor_enabled) {
                return response()->json([
                    'success' => false,
                    'message' => '2FA is not enabled for this user',
                ], 422);
            }

            $secret = decrypt($user->two_factor_secret);

            // Verify TOTP code
            if ($this->google2fa->verifyKey($secret, $request->code)) {
                return response()->json([
                    'success' => true,
                    'message' => '2FA verification successful',
                ]);
            }

            // Check recovery codes
            $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            if (in_array($request->code, $recoveryCodes)) {
                // Remove used recovery code
                $recoveryCodes = array_filter($recoveryCodes, fn($code) => $code !== $request->code);
                $user->update([
                    'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => '2FA verification successful (recovery code used)',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify 2FA code',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate new recovery codes
     */
    public function generateRecoveryCodes(Request $request = null)
    {
        try {
            $user = Auth::user();

            // Verify password if provided
            if ($request && $request->has('password')) {
                if (!Hash::check($request->password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid password',
                    ], 422);
                }
            }

            $recoveryCodes = $this->generateRandomCodes();

            // Save new recovery codes
            $user->update([
                'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Recovery codes have been regenerated',
                'data' => [
                    'recovery_codes' => $recoveryCodes,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate recovery codes',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate random recovery codes
     */
    private function generateRandomCodes($count = 10)
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(Str::random(8));
        }
        return $codes;
    }

    /**
     * Get current 2FA status
     */
    public function status()
    {
        try {
            $user = Auth::user();

            return response()->json([
                'success' => true,
                'data' => [
                    'two_factor_enabled' => $user->two_factor_enabled,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get 2FA status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

