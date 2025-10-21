<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\User\UserInfoResource;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    //
    

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
            } else {
                $user = User::firstOrCreate(
                    ['email' => $socialUser->getEmail()],
                    [
                        'name' => $socialUser->getName(),
                        'email_verified_at' => now(),
                    ]
                );
                $user->socialAccounts()->create([
                    'provider_name' => $provider,
                    'provider_id' => $socialUser->getId(),
                    'provider_token' => $socialUser->token,
                ]);
            }
            $token = $user->createToken('authToken')->accessToken;

            return (new UserInfoResource($user))
                ->additional([
                    'response_code' => 200,
                    'status' => 'success',
                    'token_type' => 'Bearer',
                    'token' => $token,
                ]);
        } catch (\Exception $e) {
            Log::error("Social Login Error ($provider): ".$e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status' => 'error',
                'message' => "Login via $provider failed",
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
