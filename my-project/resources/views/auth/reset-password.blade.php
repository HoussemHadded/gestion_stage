@extends('layouts.guest')

@section('title', 'Reinitialiser le mot de passe')

@section('content')

<h2 class="text-3xl font-bold text-white text-center mb-1">Reinitialiser le mot de passe</h2>
<p class="text-gray-300 text-center mb-8 text-sm">Choisissez un nouveau mot de passe securise.</p>

<form method="POST" action="{{ route('password.update') }}" class="space-y-6">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label for="email" class="block text-sm font-medium text-gray-200 mb-2">Adresse email</label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email', $email) }}"
               required
               autofocus
               placeholder="vous@exemple.com"
               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner @error('email') border-red-500 focus:ring-red-500 @enderror">
        @error('email')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-200 mb-2">Nouveau mot de passe</label>
        <input type="password"
               id="password"
               name="password"
               required
               placeholder="••••••••"
               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner @error('password') border-red-500 focus:ring-red-500 @enderror">
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
               placeholder="••••••••"
               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner">
    </div>

    <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900 transition-all transform hover:-translate-y-0.5">
        <i class="bi bi-shield-lock mr-2"></i>Mettre a jour le mot de passe
    </button>
</form>

@endsection
