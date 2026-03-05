<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

// -------------------------
// PUBLIC : Login (pas besoin de token)
// -------------------------
Route::post('/login', [AuthController::class, 'login']);

// -------------------------
// PROTÉGÉ : Nécessite un token Sanctum valide
// -------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Déconnexion (révoque le token courant)
    Route::post('/logout', [AuthController::class, 'logout']);

    // Informations de l'utilisateur connecté
    Route::get('/me', [AuthController::class, 'me']);

    // Alias Laravel classique
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});