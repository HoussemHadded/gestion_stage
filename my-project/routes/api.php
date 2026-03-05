<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;

// -------------------------
// LOGIN (API Token)
// -------------------------
Route::post('/login', [AuthController::class, 'login']);

// -------------------------
// GET CURRENT USER (auth:sanctum)
// -------------------------
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});