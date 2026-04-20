<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'timezone' => $data['timezone'] ?? null,
            'settings' => [
                'language' => 'en',
                'notifications' => ['email' => true, 'browser' => true],
            ],
        ]);

        $token = $user->createToken('vuejobs-api')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        /** @var User $user */
        $user = $request->user();
        $token = $user->createToken('vuejobs-api-' . Str::random(6))->accessToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }

    public function logout(): JsonResponse
    {
        $token = request()->user()?->token();
        if ($token) {
            $token->revoke();
        }

        return response()->json(['message' => 'Logged out']);
    }

    /**
     * Return the Google OAuth redirect URL for the frontend to open.
     */
    public function googleRedirect(): JsonResponse
    {
        $params = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => config('services.google.redirect'),
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'consent',
        ]);

        return response()->json([
            'url' => 'https://accounts.google.com/o/oauth2/v2/auth?' . $params,
        ]);
    }

    /**
     * Handle the Google OAuth callback — exchange code for user info, create/login user.
     */
    public function googleCallback(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string']);

        // Exchange authorization code for tokens
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code' => $request->input('code'),
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri' => config('services.google.redirect'),
            'grant_type' => 'authorization_code',
        ]);

        if ($tokenResponse->failed()) {
            return response()->json(['message' => 'Google authentication failed'], 422);
        }

        $accessToken = $tokenResponse->json('access_token');

        // Fetch user info
        $googleUser = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v3/userinfo')
            ->json();

        if (empty($googleUser['sub'])) {
            return response()->json(['message' => 'Unable to retrieve Google profile'], 422);
        }

        // Find or create user
        $user = User::where('google_id', $googleUser['sub'])
            ->orWhere('email', $googleUser['email'])
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser['sub'],
                'avatar_url' => $user->avatar_url ?? ($googleUser['picture'] ?? null),
            ]);
        } else {
            $user = User::create([
                'name' => $googleUser['name'],
                'email' => $googleUser['email'],
                'google_id' => $googleUser['sub'],
                'password' => Hash::make(Str::random(32)),
                'avatar_url' => $googleUser['picture'] ?? null,
                'role' => 'developer',
                'email_verified_at' => now(),
                'settings' => [
                    'language' => 'en',
                    'notifications' => ['email' => true, 'browser' => true],
                ],
            ]);
        }

        $token = $user->createToken('vuejobs-google')->accessToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ]);
    }
}
