<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * POST /api/login
     * Authentifie un utilisateur et retourne un token Sanctum.
     */
    public function login(Request $request)
    {
        // Validation des champs
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Vérifier les identifiants
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Créer un token personnel Sanctum
        $token = $user->createToken('postman')->plainTextToken;

        return response()->json([
            'message'    => 'Login successful',
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => $user,
        ], 200);
    }

    /**
     * POST /api/logout
     * Supprime le token courant de l'utilisateur authentifié.
     * Protégé par auth:sanctum.
     */
    public function logout(Request $request)
    {
        // Révoquer le token utilisé pour cette requête
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    /**
     * GET /api/me
     * Retourne les informations de l'utilisateur authentifié.
     * Protégé par auth:sanctum.
     */
    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ], 200);
    }
}