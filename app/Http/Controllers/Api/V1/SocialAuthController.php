<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        try {
            $socialUser = $this->getSocialUser($provider);

            [$user, $generatedPassword] = $this->findOrCreateUser($provider, $socialUser);

            $this->linkSocialAccountIfNeeded($provider, $socialUser, $user);

            if ($user->hasTwoFactorEnabled()) {
                $callbackUrl = config('app.url').'/admin/social-callback';

                return redirect(
                    $callbackUrl.'?two_factor_required=1'
                    .'&user_id='.$user->id
                    .'&email='.urlencode($user->email)
                );
            }

            $token = $user->createToken('authToken')->accessToken;

            $redirectUrl = $this->buildRedirectUrl(
                user: $user,
                token: $token,
                generatedPassword: $generatedPassword
            );

            return redirect($redirectUrl);

        } catch (\Exception $e) {
            Log::error("Social Login Error ({$provider}): {$e->getMessage()}");

            $callbackUrl = config('app.url').'/admin/social-callback';

            return redirect(
                $callbackUrl.'?error='.urlencode("Login via $provider failed: ".$e->getMessage())
            );
        }
    }

    private function getSocialUser(string $provider)
    {
        return Socialite::driver($provider)->stateless()->user();
    }

    private function findOrCreateUser(string $provider, $socialUser): array
    {
        $existingAccount = SocialAccount::where('provider_id', $socialUser->getId())
            ->where('provider_name', $provider)
            ->first();

        if ($existingAccount) {
            return [$existingAccount->user, null];
        }

        $generatedPassword = $this->generateRandomPassword(25);

        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'No Name',
                'email_verified_at' => now(),
                'password' => Hash::make($generatedPassword),
            ]
        );

        return [$user, $generatedPassword];
    }

    private function linkSocialAccountIfNeeded(string $provider, $socialUser, User $user): void
    {
        $exists = SocialAccount::where('provider_id', $socialUser->getId())
            ->where('provider_name', $provider)
            ->exists();

        if (! $exists) {
            $user->socialAccounts()->create([
                'provider_name' => $provider,
                'provider_id' => $socialUser->getId(),
                'provider_token' => $socialUser->token ?? null,
            ]);
        }
    }

    private function buildRedirectUrl(User $user, string $token, ?string $generatedPassword): string
    {
        $callbackUrl = config('app.url').'/admin/social-callback';

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'two_factor_enabled' => $user->two_factor_enabled,
        ];

        // 2FA Redirect
        if ($user->two_factor_enabled) {
            return $callbackUrl
                .'?two_factor_required=true'
                .'&user_id='.$user->id
                .'&user='.urlencode(json_encode($userData));
        }

        // Normal Login Redirect
        $url = $callbackUrl
            .'?token='.urlencode($token)
            .'&user='.urlencode(json_encode($userData));

        if ($generatedPassword) {
            $url .= '&generated_password='.urlencode($generatedPassword);
        }

        return $url;
    }

    private function generateRandomPassword(int $length = 25): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_[]{}<>~`+=,.;:?';
        $password = '';

        // Required Characters
        $password .= $this->getRandomChar('abcdefghijklmnopqrstuvwxyz');
        $password .= $this->getRandomChar('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $password .= $this->getRandomChar('0123456789');
        $password .= $this->getRandomChar('!@#$%^&*()-_[]{}<>~`+=,.;:?');

        // Fill Rest
        for ($i = 4; $i < $length; $i++) {
            $password .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return str_shuffle($password);
    }

    private function getRandomChar(string $chars): string
    {
        return $chars[random_int(0, strlen($chars) - 1)];
    }
}
