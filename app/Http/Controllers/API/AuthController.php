<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signIn()
    {
        return response()->json(['message' => 'Sign in form data']);
    }

    public function registr(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'email'=> 'email|required|unique:App\Models\User',
            'email' => 'email|required|unique:users',
            'password' => 'required|min:6'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return response()->json(['message' => 'Registration successful', 'user' => $user], 201);
    }

    public function login()
    {
        return response()->json(['message' => 'Login form data']);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'email|required',
            'password' => 'required|min:6'
        ]);

        // Для API используем только токены, без сессий
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Предоставленные учетные данные не соответствуют нашим записям.',
                'errors' => ['email' => 'Предоставленные учетные данные не соответствуют нашим записям.']
            ], 401);
        }

        // Создаем токен для API
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        // Для API используем только токены, без сессий
        $user = $request->user();
        if ($user) {
            // Удаляем текущий токен (токен из заголовка Authorization)
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Logout successful']);
    }
}