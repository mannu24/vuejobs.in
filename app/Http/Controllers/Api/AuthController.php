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
     * Return the Google OAuth URL for the frontend to redirect the user to.
     *
     * The redirect_uri points to the FRONTEND callback page, not the backend.
     * Google will redirect the user back to the frontend with ?code=xxx,
     * then the frontend POSTs that code to /api/auth/google/callback.
     */
    public function googleRedirect(): JsonResponse
    {
        $redirectUri = config('services.google.redirect');

        $params = http_build_query([
            'client_id'     => config('services.google.client_id'),
            'redirect_uri'  => $redirectUri,
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'access_type'   => 'offline',
            'prompt'        => 'consent',
        ]);

        return response()->json([
            'url' => 'https://accounts.google.com/o/oauth2/v2/auth?' . $params,
        ]);
    }

    /**
     * Exchange the Google authorization code for user info, find or create user, return token.
     *
     * The frontend sends the code it received from Google's redirect.
     * We use the same redirect_uri here that was used in the authorize request
     * (Google requires an exact match).
     */
    public function googleCallback(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'role' => 'nullable|string|in:developer,employer,recruiter',
        ]);

        $redirectUri = config('services.google.redirect');

        // Exchange authorization code for tokens
        $tokenResponse = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'code'          => $request->input('code'),
            'client_id'     => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret'),
            'redirect_uri'  => $redirectUri,
            'grant_type'    => 'authorization_code',
        ]);

        if ($tokenResponse->failed()) {
            return response()->json([
                'message' => 'Google authentication failed. Please try again.',
            ], 422);
        }

        $accessToken = $tokenResponse->json('access_token');

        if (! $accessToken) {
            return response()->json([
                'message' => 'No access token received from Google.',
            ], 422);
        }

        // Fetch user profile from Google
        $googleUser = Http::withToken($accessToken)
            ->get('https://www.googleapis.com/oauth2/v3/userinfo')
            ->json();

        if (empty($googleUser['sub']) || empty($googleUser['email'])) {
            return response()->json([
                'message' => 'Unable to retrieve Google profile.',
            ], 422);
        }

        // Find existing user by google_id or email
        $user = User::where('google_id', $googleUser['sub'])->first()
            ?? User::where('email', $googleUser['email'])->first();

        $isNewUser = false;

        if ($user) {
            // Link Google account if not already linked
            $user->update([
                'google_id'  => $googleUser['sub'],
                'avatar_url' => $user->avatar_url ?: ($googleUser['picture'] ?? null),
            ]);
        } else {
            // Create new user
            $isNewUser = true;
            $user = User::create([
                'name'              => $googleUser['name'] ?? $googleUser['email'],
                'email'             => $googleUser['email'],
                'google_id'         => $googleUser['sub'],
                'password'          => null,
                'avatar_url'        => $googleUser['picture'] ?? null,
                'role'              => $request->input('role', 'developer'),
                'email_verified_at' => now(),
                'settings'          => [
                    'language'      => 'en',
                    'notifications' => ['email' => true, 'browser' => true],
                ],
            ]);
        }

        $token = $user->createToken('vuejobs-google')->accessToken;

        return response()->json([
            'token'    => $token,
            'user'     => new UserResource($user),
            'is_new'   => $isNewUser,
        ]);
    }
}
