<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // If request comes from API (expects JSON), return token
        if ($request->expectsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }

        // Otherwise it's web login
        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        if ($request->expectsJson()) {
            $request->user()->tokens()->delete();

            return response()->json([
                'message' => 'Logged out'
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}