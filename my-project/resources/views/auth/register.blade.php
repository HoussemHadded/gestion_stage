@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')

<h2 class="text-3xl font-bold text-white text-center mb-1">Créer un compte</h2>
<p class="text-gray-300 text-center mb-8 text-sm">Rejoignez la plateforme GestionStages</p>

<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium text-gray-200 mb-2">Nom complet</label>
        <input type="text"
               id="name"
               name="name"
               value="{{ old('name') }}"
               required
               autofocus
               placeholder="Votre nom et prénom"
               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner @error('name') border-red-500 @enderror">
        @error('name')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-200 mb-2">Adresse email</label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               placeholder="vous@exemple.com"
               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner @error('email') border-red-500 @enderror">
        @error('email')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="role" class="block text-sm font-medium text-gray-200 mb-2">Profil</label>
        <select id="role"
                name="role"
                class="w-full bg-gray-800 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner @error('role') border-red-500 @enderror"
                required>
            <option value="" class="text-gray-400">-- Sélectionner votre profil --</option>
            <option value="student"    {{ old('role') === 'student'    ? 'selected' : '' }}>🎓 Étudiant</option>
            <option value="entreprise" {{ old('role') === 'entreprise' ? 'selected' : '' }}>🏢 Entreprise</option>
        </select>
        @error('role')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-200 mb-2">Mot de passe</label>
        <input type="password"
               id="password"
               name="password"
               required
               placeholder="Minimum 8 caractères"
               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner @error('password') border-red-500 @enderror">
        @error('password')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-200 mb-2">Confirmer le mot de passe</label>
        <input type="password"
               id="password_confirmation"
               name="password_confirmation"
               required
               placeholder="Répétez votre mot de passe"
               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner">
    </div>

    <button type="submit" id="btn-register" class="w-full mt-2 flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900 transition-all transform hover:-translate-y-0.5">
        <i class="bi bi-person-plus-fill mr-2"></i>Créer mon compte
    </button>
</form>

<hr class="border border-white/10 my-8">

<p class="text-center text-sm text-gray-400">
    Déjà inscrit ?
    <a href="{{ route('login') }}" class="font-medium text-indigo-400 hover:text-indigo-300 transition">Se connecter &rarr;</a>
</p>

@endsection
