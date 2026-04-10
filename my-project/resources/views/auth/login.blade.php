@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')

<h2 class="page-title text-center mb-1">Bon retour</h2>
<p class="page-subtitle text-center mb-4">Connectez-vous à votre espace</p>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               autofocus
               placeholder="vous@exemple.com"
               class="form-control @error('email') is-invalid @enderror">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password"
               id="password"
               name="password"
               required
               placeholder="••••••••"
               class="form-control @error('password') is-invalid @enderror">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4 d-flex align-items-center justify-content-between">
        <div class="form-check mb-0">
            <input type="checkbox"
                   class="form-check-input"
                   id="remember"
                   name="remember"
                   {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Se souvenir de moi</label>
        </div>
    </div>

    <button type="submit" id="btn-login" class="btn btn-primary-gradient">
        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
    </button>
</form>

<hr class="divider">

<p class="text-center mb-0" style="color: rgba(255,255,255,0.6); font-size:0.875rem;">
    Pas encore de compte ?
    <a href="{{ route('register') }}" class="text-link ms-1">Créer un compte →</a>
</p>

@endsection
