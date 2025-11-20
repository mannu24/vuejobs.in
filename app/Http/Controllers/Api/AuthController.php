<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
                'notifications' => [
                    'email' => true,
                    'browser' => true,
                ],
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

        /** @var \App\Models\User $user */
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
}

