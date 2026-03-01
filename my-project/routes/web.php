<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OffreController;
use App\Http\Controllers\CandidatureController;

// Home
Route::get('/', function () {
    return view('welcome');
});

// Users
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// Offres
Route::get('/offres', [OffreController::class, 'index'])->name('offres.index');
Route::get('/offres/create', [OffreController::class, 'create'])->name('offres.create');
Route::post('/offres', [OffreController::class, 'store'])->name('offres.store');
Route::get('/offres/{id}/edit', [OffreController::class, 'edit'])->name('offres.edit');
Route::put('/offres/{id}', [OffreController::class, 'update'])->name('offres.update');
Route::delete('/offres/{id}', [OffreController::class, 'destroy'])->name('offres.destroy');

// Candidatures
Route::get('/candidatures', [CandidatureController::class, 'index'])->name('candidatures.index');
Route::get('/candidatures/create', [CandidatureController::class, 'create'])->name('candidatures.create');
Route::post('/candidatures', [CandidatureController::class, 'store'])->name('candidatures.store');
Route::get('/candidatures/{id}/edit', [CandidatureController::class, 'edit'])->name('candidatures.edit');
Route::put('/candidatures/{id}', [CandidatureController::class, 'update'])->name('candidatures.update');
Route::delete('/candidatures/{id}', [CandidatureController::class, 'destroy'])->name('candidatures.destroy');