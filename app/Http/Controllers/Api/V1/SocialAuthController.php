<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

            [$user, $generatedPassword, $needsPasswordSetup] = $this->findOrCreateUser($provider, $socialUser);

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
                needsPasswordSetup: $needsPasswordSetup
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
        Log::info('=== findOrCreateUser started ===', [
            'provider' => $provider,
            'social_email' => $socialUser->getEmail(),
            'social_id' => $socialUser->getId(),
        ]);

        $existingAccount = SocialAccount::where('provider_id', $socialUser->getId())
            ->where('provider_name', $provider)
            ->first();

        if ($existingAccount) {
            $user = $existingAccount->user;

            // Check if user has set their own password
            $needsPasswordSetup = is_null($user->password_set_at);

            Log::info('Existing social account found', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'password_set_at' => $user->password_set_at,
                'needs_password_setup' => $needsPasswordSetup,
            ]);

            return [$user, null, $needsPasswordSetup];
        }

        // Check if user already exists by email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Check if user has set their own password
            $needsPasswordSetup = is_null($user->password_set_at);

            Log::info('User exists by email but no social account', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'password_set_at' => $user->password_set_at,
                'needs_password_setup' => $needsPasswordSetup,
            ]);

            return [$user, null, $needsPasswordSetup];
        }

        // Create new user with temporary unusable password
        Log::info('Creating new user from social login', [
            'email' => $socialUser->getEmail(),
            'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'No Name',
        ]);

        $user = User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'No Name',
            'email' => $socialUser->getEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make(Str::random(64)), // Temporary password
            'password_set_at' => null, // User hasn't set password yet
        ]);

        Log::info('New user created - needs password setup', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'needs_password_setup' => true,
        ]);

        return [$user, null, true]; // true = needs password setup
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

    private function buildRedirectUrl(User $user, string $token, bool $needsPasswordSetup = false): string
    {
        $callbackUrl = config('app.url').'/admin/social-callback';

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'two_factor_enabled' => $user->two_factor_enabled,
        ];

        Log::info('=== buildRedirectUrl ===', [
            'user_id' => $user->id,
            'needs_password_setup' => $needsPasswordSetup,
            'two_factor_enabled' => $user->two_factor_enabled,
        ]);

        // 2FA Redirect
        if ($user->two_factor_enabled) {
            $url = $callbackUrl
                .'?two_factor_required=true'
                .'&user_id='.$user->id
                .'&user='.urlencode(json_encode($userData));

            Log::info('Redirecting to 2FA', ['url' => $url]);
            return $url;
        }

        // Password Setup Required for New Users
        if ($needsPasswordSetup) {
            $url = $callbackUrl
                .'?needs_password_setup=true'
                .'&token='.urlencode($token)
                .'&user='.urlencode(json_encode($userData));

            Log::info('Redirecting to password setup', ['url' => $url]);
            return $url;
        }

        // Normal Login Redirect
        $url = $callbackUrl
            .'?token='.urlencode($token)
            .'&user='.urlencode(json_encode($userData));

        Log::info('Redirecting to normal login', ['url' => $url]);
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
