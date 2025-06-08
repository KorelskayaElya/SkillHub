<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);
        auth()->login($user);

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        auth()->login($user);

        return response()->json(['message' => 'Logged in'], 200);
    }

    public function user(Request $request)
    {
        \Log::info('COOKIES:', $request->cookies->all());
        \Log::info('User is:', [$request->user()]);

        if (!$request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        return response()->json($request->user());
    }


    public function logout(Request $request)
    {
        auth()->logout();
        return response()->json(['message' => 'Logged out']);
    }
}