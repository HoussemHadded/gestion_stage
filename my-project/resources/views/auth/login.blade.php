@extends('layouts.guest')

@section('title', 'Connexion')

@section('content')

<h2 class="text-3xl font-bold text-white text-center mb-1">Bon retour</h2>
<p class="text-gray-300 text-center mb-8 text-sm">Connectez-vous à votre espace</p>

<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <div>
        <label for="email" class="block text-sm font-medium text-gray-200 mb-2">Adresse email</label>
        <input type="email"
               id="email"
               name="email"
               value="{{ old('email') }}"
               required
               autofocus
               placeholder="vous@exemple.com"
               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition shadow-inner @error('email') border-red-500 focus:ring-red-500 @enderror">
        @error('email')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-200 mb-2">Mot de passe</label>
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

    <div class="flex items-center justify-between">
        <label class="flex items-center">
            <input type="checkbox"
                   name="remember"
                   class="w-4 h-4 rounded border-gray-600 text-indigo-500 focus:ring-indigo-500 bg-white/5"
                   {{ old('remember') ? 'checked' : '' }}>
            <span class="ml-2 text-sm text-gray-300">Se souvenir de moi</span>
        </label>
    </div>

    <button type="submit" id="btn-login" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900 transition-all transform hover:-translate-y-0.5">
        <i class="bi bi-box-arrow-in-right mr-2"></i>Se connecter
    </button>
</form>

<hr class="border border-white/10 my-8">

<p class="text-center text-sm text-gray-400">
    Pas encore de compte ?
    <a href="{{ route('register') }}" class="font-medium text-indigo-400 hover:text-indigo-300 transition">Créer un compte &rarr;</a>
</p>

@endsection
