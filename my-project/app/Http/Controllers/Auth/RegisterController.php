<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        \Illuminate\Support\Facades\Log::info('[RegisterController] Registration flow started', [
            'payload' => $request->except(['password', 'password_confirmation']),
        ]);

        try {
            $user = $this->userService->store($request->validated());

            if (!$user || !$user->id) {
                \Illuminate\Support\Facades\Log::error('[RegisterController] User not inserted or ID missing');
                return back()->withInput()->withErrors(['error' => 'Erreur lors de la création du compte.']);
            }

            Auth::login($user);

            $request->session()->regenerate();

            \Illuminate\Support\Facades\Log::info('[RegisterController] Registration successful, user logged in', ['user_id' => $user->id]);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[RegisterController] Registration exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->withErrors(['error' => 'Une erreur est survenue lors de l\'inscription.']);
        }
    }
}
