<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\{Auth,Log};
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Http\Resources\Api\V1\User\UserInfoResource;

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

            // ابحث عن المستخدم أو أنشئ جديد
            $user = User::firstOrCreate(
                ['email' => $socialUser->getEmail()],
                ['name' => $socialUser->getName()]
            );

            // إنشاء Access Token باستخدام Passport
            $token = $user->createToken('authToken')->accessToken;

            // إعادة بيانات المستخدم مع التوكن
            return (new UserInfoResource($user))
                ->additional(['token' => $token]);
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
