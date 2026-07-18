<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return response()->json(['data' => $user], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($field, $data['login'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'errors' => ['message' => 'Unauthorized'],
            ], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['data' => ['token' => $token]]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['data' => ['message' => 'Logout success']]);
    }

    public function current(Request $request): JsonResponse
    {
        return response()->json(['data' => $request->user()]);
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $user->fill($data)->save();

        return response()->json(['data' => $user]);
    }
}
