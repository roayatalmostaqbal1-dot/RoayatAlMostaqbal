<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\{Log, Hash};
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $account = SocialAccount::where('provider_id', $socialUser->getId())
                ->where('provider_name', $provider)
                ->first();
            if ($account) {
                $user = $account->user;
                $generatedPassword = null;
            } else {
                $generatedPassword = $this->generateRandomPassword(25);
                $user = User::firstOrCreate(
                    ['email' => $socialUser->getEmail()],
                    [
                        'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'No Name',
                        'email_verified_at' => now(),
                        'password' => Hash::make($generatedPassword),
                    ]
                );
                $existingAccount = SocialAccount::where('provider_id', $socialUser->getId())
                    ->where('provider_name', $provider)
                    ->first();

                if (!$existingAccount) {
                    $user->socialAccounts()->create([
                        'provider_name' => $provider,
                        'provider_id' => $socialUser->getId(),
                        'provider_token' => $socialUser->token ?? null,
                    ]);
                }
            }
            $token = $user->createToken('authToken')->accessToken;
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'two_factor_enabled' => $user->two_factor_enabled,
            ];

            $callbackUrl = config('app.url') . '/admin/social-callback';

            if ($user->two_factor_enabled) {
                $redirectUrl = $callbackUrl . '?two_factor_required=true&user_id=' . $user->id . '&user=' . urlencode(json_encode($userData));
            } else {
                $redirectUrl = $callbackUrl . '?token=' . urlencode($token) . '&user=' . urlencode(json_encode($userData));
                if ($generatedPassword) {
                    $redirectUrl .= '&generated_password=' . urlencode($generatedPassword);
                }
            }

            return redirect($redirectUrl);

        } catch (\Exception $e) {
            Log::error("Social Login Error ($provider): " . $e->getMessage());
            $callbackUrl = config('app.url') . '/admin/social-callback';
            $redirectUrl = $callbackUrl . '?error=' . urlencode("Login via $provider failed: " . $e->getMessage());
            return redirect($redirectUrl);
        }
    }

    private function generateRandomPassword(int $length = 25): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_[]{}<>~`+=,.;:?';
        $maxIndex = strlen($chars) - 1;
        $password = '';
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';
        $symbols = '!@#$%^&*()-_[]{}<>~`+=,.;:?';
        $password .= $lower[random_int(0, strlen($lower) - 1)];
        $password .= $upper[random_int(0, strlen($upper) - 1)];
        $password .= $digits[random_int(0, strlen($digits) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];
        for ($i = 4; $i < $length; $i++) {
            $password .= $chars[random_int(0, $maxIndex)];
        }

        return str_shuffle($password);
    }
}
