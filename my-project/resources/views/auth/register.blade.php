@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')

<h2 class="page-title text-center mb-1">Créer un compte</h2>
<p class="page-subtitle text-center mb-4">Rejoignez la plateforme GestionStages</p>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Nom complet</label>
        <input type="text"
               id="name"
               name="name"
               value="{{ old('name') }}"
               required
               autofocus
               placeholder="Votre nom et prénom"
               class="form-control @error('name') is-invalid @enderror">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Adresse email</label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               placeholder="vous@exemple.com"
               class="form-control @error('email') is-invalid @enderror">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Profil</label>
        <select id="role"
                name="role"
                class="form-select @error('role') is-invalid @enderror"
                required>
            <option value="">-- Sélectionner votre profil --</option>
            <option value="student"    {{ old('role') === 'student'    ? 'selected' : '' }}>🎓 Étudiant</option>
            <option value="entreprise" {{ old('role') === 'entreprise' ? 'selected' : '' }}>🏢 Entreprise</option>
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Mot de passe</label>
        <input type="password"
               id="password"
               name="password"
               required
               placeholder="Minimum 8 caractères"
               class="form-control @error('password') is-invalid @enderror">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
        <input type="password"
               id="password_confirmation"
               name="password_confirmation"
               required
               placeholder="Répétez votre mot de passe"
               class="form-control">
    </div>

    <button type="submit" id="btn-register" class="btn btn-primary-gradient">
        <i class="bi bi-person-plus-fill me-2"></i>Créer mon compte
    </button>
</form>

<hr class="divider">

<p class="text-center mb-0" style="color: rgba(255,255,255,0.6); font-size:0.875rem;">
    Déjà inscrit ?
    <a href="{{ route('login') }}" class="text-link ms-1">Se connecter →</a>
</p>

@endsection
