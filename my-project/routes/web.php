<?php

// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Student;
use App\Http\Controllers\Company;
use App\Http\Controllers\Entreprise;
use App\Http\Controllers\Auth;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Route principale — Redirection intelligente
|--------------------------------------------------------------------------
| Guest   → /login
| Auth    → /dashboard (qui redirige selon le rôle)
*/
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentification — Routes invité uniquement
|--------------------------------------------------------------------------
*/
Route::middleware('auth.guest')->group(function () {
    Route::get('/login',    [Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',   [Auth\LoginController::class, 'login'])->middleware('throttle:10,1');

    Route::get('/register', [Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register',[Auth\RegisterController::class, 'register']);
});

// Déconnexion
Route::post('/logout', [Auth\LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Route centrale — Redirige vers le bon dashboard selon le rôle
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Gestion des utilisateurs
        Route::get('/users',           [Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/create',    [Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users',          [Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}',      [Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}',   [Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Gestion des offres (côté admin)
        Route::get('/offres',              [Admin\OffreController::class, 'index'])->name('offres.index');
        Route::get('/offres/create',       [Admin\OffreController::class, 'create'])->name('offres.create');
        Route::post('/offres',             [Admin\OffreController::class, 'store'])->name('offres.store');
        Route::get('/offres/{id}/edit',    [Admin\OffreController::class, 'edit'])->name('offres.edit');
        Route::put('/offres/{id}',         [Admin\OffreController::class, 'update'])->name('offres.update');
        Route::delete('/offres/{id}',      [Admin\OffreController::class, 'destroy'])->name('offres.destroy');
    });

/*
|--------------------------------------------------------------------------
| ENTREPRISE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:entreprise'])
    ->prefix('entreprise')
    ->name('entreprise.')
    ->group(function () {
        Route::get('/dashboard', [Company\DashboardController::class, 'index'])->name('dashboard');

        // Gestion des offres (côté entreprise)
        Route::get('/offres',              [Entreprise\OffreController::class, 'index'])->name('offres.index');
        Route::get('/offres/create',       [Entreprise\OffreController::class, 'create'])->name('offres.create');
        Route::post('/offres',             [Entreprise\OffreController::class, 'store'])->name('offres.store');
        Route::get('/offres/{id}/edit',    [Entreprise\OffreController::class, 'edit'])->name('offres.edit');
        Route::put('/offres/{id}',         [Entreprise\OffreController::class, 'update'])->name('offres.update');
        Route::delete('/offres/{id}',      [Entreprise\OffreController::class, 'destroy'])->name('offres.destroy');

        // Gestion des candidatures reçues
        Route::get('/candidatures',        [Company\CandidatureController::class, 'index'])->name('candidatures.index');
        Route::patch('/candidatures/{candidature}/statut', [Company\CandidatureController::class, 'updateStatut'])->name('candidatures.updateStatut');
    });

/*
|--------------------------------------------------------------------------
| ÉTUDIANT
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');

        // Consulter les offres
        Route::get('/offres', [Admin\OffreController::class, 'index'])->name('offres.index');

        // Candidatures
        Route::get('/candidatures/create', [Student\CandidatureController::class, 'create'])->name('candidatures.create');
        Route::post('/candidatures',       [Student\CandidatureController::class, 'store'])->name('candidatures.store');
    });