<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Student;
use App\Http\Controllers\Company;
use App\Http\Controllers\Entreprise;
use App\Http\Controllers\Supervisor;
use App\Http\Controllers\Auth;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// -------------------------
// AUTHENTICATION (Web)
// -------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', [Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [Auth\LoginController::class, 'login'])->middleware('throttle:10,1');

    Route::get('/register', [Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [Auth\RegisterController::class, 'register']);
});

Route::post('/logout', [Auth\LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// -------------------------
// ADMIN ROUTES
// -------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('admin.dashboard');

    // User Management
    Route::get('/users',              [Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create',       [Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users',             [Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit',    [Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}',         [Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}',      [Admin\UserController::class, 'destroy'])->name('users.destroy');

    // Admin-side Offer Management
    Route::get('/offres',             [Admin\OffreController::class, 'index'])->name('admin.offres.index');
    Route::get('/offres/create',      [Admin\OffreController::class, 'create'])->name('admin.offres.create');
    Route::post('/offres',            [Admin\OffreController::class, 'store'])->name('admin.offres.store');
    Route::get('/offres/{id}/edit',   [Admin\OffreController::class, 'edit'])->name('admin.offres.edit');
    Route::put('/offres/{id}',        [Admin\OffreController::class, 'update'])->name('admin.offres.update');
    Route::delete('/offres/{id}',     [Admin\OffreController::class, 'destroy'])->name('admin.offres.destroy');
});

// -------------------------
// ENTREPRISE ROUTES
// -------------------------
Route::middleware(['auth', 'role:entreprise'])->prefix('entreprise')->group(function () {
    Route::get('/dashboard', [Company\DashboardController::class, 'index'])->name('entreprise.dashboard');

    // Offer Management — dedicated Entreprise\OffreController (Phase 3)
    Route::get('/offres',             [Entreprise\OffreController::class, 'index'])->name('offres.index');
    Route::get('/offres/create',      [Entreprise\OffreController::class, 'create'])->name('offres.create');
    Route::post('/offres',            [Entreprise\OffreController::class, 'store'])->name('offres.store');
    Route::get('/offres/{id}/edit',   [Entreprise\OffreController::class, 'edit'])->name('offres.edit');
    Route::put('/offres/{id}',        [Entreprise\OffreController::class, 'update'])->name('offres.update');
    Route::delete('/offres/{id}',     [Entreprise\OffreController::class, 'destroy'])->name('offres.destroy');

    // Candidature Management
    Route::get('/candidatures', [Company\CandidatureController::class, 'index'])->name('candidatures.index');
    Route::patch('/candidatures/{candidature}/statut', [Company\CandidatureController::class, 'updateStatut'])->name('candidatures.updateStatut');
});

// -------------------------
// STUDENT ROUTES
// -------------------------
Route::middleware(['auth', 'role:student'])->prefix('student')->group(function () {
    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('student.dashboard');

    Route::get('/offres', [Admin\OffreController::class, 'index'])->name('student.offres.index');

    Route::get('/candidatures/create',  [Student\CandidatureController::class, 'create'])->name('candidatures.create');
    Route::post('/candidatures',        [Student\CandidatureController::class, 'store'])->name('candidatures.store');
});

// -------------------------
// ENCADRANT ROUTES
// -------------------------
Route::middleware(['auth', 'role:encadrant'])->prefix('encadrant')->name('encadrant.')->group(function () {
    Route::get('/dashboard', [Supervisor\DashboardController::class, 'index'])->name('dashboard');

    // Phase 4 — Évaluations
    // Named: encadrant.evaluations.index / .create / .store / .edit / .update / .destroy
    Route::resource('evaluations', Supervisor\EvaluationController::class);
});