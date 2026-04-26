@extends('layouts.app')
@section('title', 'Mon Profil')
@section('content')

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-3xl font-black text-white text-white tracking-tight flex items-center gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-gold-primary to-gold-soft text-black rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                <i data-lucide="user" class="text-white text-sm inline-block"></i>
            </div>
            Mon Profil
        </h2>
        <p class="mt-2 text-sm text-luxury-muted dark:text-slate-400 font-medium">Gérez vos informations personnelles et paramètres de compte.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Left column: Profile Card --}}
    <div class="lg:col-span-1">
        <div class="glass-card rounded-3xl p-6 text-center">
            <div class="w-24 h-24 mx-auto bg-gradient-to-br from-gold-primary to-gold-soft text-black rounded-3xl flex items-center justify-center font-black text-white text-4xl shadow-xl shadow-indigo-500/30 mb-5 relative">
                {{ mb_substr(auth()->user()->name, 0, 1) }}
                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-luxury-surface2 dark:bg-luxury-surface2 rounded-xl flex items-center justify-center border-4 border-white dark:border-slate-900">
                    <i data-lucide="shield-check" class="text-gold-primary text-xs inline-block"></i>
                </div>
            </div>
            <h3 class="text-xl font-bold text-white text-white">{{ auth()->user()->name }}</h3>
            <p class="text-sm text-luxury-muted dark:text-slate-400 mt-1 mb-4">{{ auth()->user()->email }}</p>
            
            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-luxury-surface2 dark:bg-luxury-surface2/50 text-luxury-muted dark:text-slate-300 border border-luxury-borderSoft dark:border-luxury-borderSoft/50 uppercase tracking-widest">
                {{ auth()->user()->role }}
            </div>

            @if(auth()->user()->isEntreprise() && auth()->user()->company_name)
                <div class="mt-6 pt-6 border-t border-luxury-border dark:border-luxury-borderSoft/50 text-left">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-3">Informations Entreprise</p>
                    <div class="flex items-start gap-3 mb-3">
                        <i data-lucide="building" class="text-gold-primary mt-0.5 inline-block"></i>
                        <div>
                            <p class="text-xs text-luxury-muted dark:text-slate-400">Nom</p>
                            <p class="text-sm font-bold text-white text-white">{{ auth()->user()->company_name }}</p>
                        </div>
                    </div>
                    @if(auth()->user()->company_address)
                    <div class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="text-amber-400 mt-0.5 inline-block"></i>
                        <div>
                            <p class="text-xs text-luxury-muted dark:text-slate-400">Adresse</p>
                            <p class="text-sm font-bold text-white text-white">{{ auth()->user()->company_address }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Right column: Edit Form --}}
    <div class="lg:col-span-2">
        <div class="glass-card rounded-3xl p-8">
            <h3 class="text-lg font-bold text-white text-white mb-6 flex items-center gap-2">
                <i data-lucide="pencil" class="text-gold-primary inline-block"></i>Modifier mes informations
            </h3>

            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf @method('PATCH')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-xs font-bold text-luxury-muted dark:text-slate-400 uppercase tracking-widest mb-2">Nom complet</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i data-lucide="user" class="text-slate-400 inline-block"></i></div>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required
                                   class="w-full bg-luxury-surface2 dark:bg-luxury-surface2/50 border border-luxury-borderSoft dark:border-luxury-borderSoft/50 rounded-xl pl-11 pr-4 py-3 text-white text-white focus:outline-none focus:ring-2 focus:ring-gold-primary/50/50 focus:border-gold-primary/50/50 transition-all text-sm @error('name') border-red-400 @enderror">
                        </div>
                        @error('name')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-luxury-muted dark:text-slate-400 uppercase tracking-widest mb-2">Adresse email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i data-lucide="mail" class="text-slate-400 inline-block"></i></div>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required
                                   class="w-full bg-luxury-surface2 dark:bg-luxury-surface2/50 border border-luxury-borderSoft dark:border-luxury-borderSoft/50 rounded-xl pl-11 pr-4 py-3 text-white text-white focus:outline-none focus:ring-2 focus:ring-gold-primary/50/50 focus:border-gold-primary/50/50 transition-all text-sm @error('email') border-red-400 @enderror">
                        </div>
                        @error('email')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                @if(auth()->user()->isEntreprise())
                    <hr class="border-luxury-border dark:border-luxury-borderSoft/50 my-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company_name" class="block text-xs font-bold text-luxury-muted dark:text-slate-400 uppercase tracking-widest mb-2">Nom de l'entreprise</label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name', auth()->user()->company_name) }}"
                                   class="w-full bg-luxury-surface2 dark:bg-luxury-surface2/50 border border-luxury-borderSoft dark:border-luxury-borderSoft/50 rounded-xl px-4 py-3 text-white text-white focus:outline-none focus:ring-2 focus:ring-gold-primary/50/50 focus:border-gold-primary/50/50 transition-all text-sm">
                        </div>
                        <div>
                            <label for="company_address" class="block text-xs font-bold text-luxury-muted dark:text-slate-400 uppercase tracking-widest mb-2">Adresse de l'entreprise</label>
                            <input type="text" name="company_address" id="company_address" value="{{ old('company_address', auth()->user()->company_address) }}"
                                   class="w-full bg-luxury-surface2 dark:bg-luxury-surface2/50 border border-luxury-borderSoft dark:border-luxury-borderSoft/50 rounded-xl px-4 py-3 text-white text-white focus:outline-none focus:ring-2 focus:ring-gold-primary/50/50 focus:border-gold-primary/50/50 transition-all text-sm">
                        </div>
                    </div>
                @endif

                <hr class="border-luxury-border dark:border-luxury-borderSoft/50 my-6">

                <div>
                    <label class="block text-xs font-bold text-luxury-muted dark:text-slate-400 uppercase tracking-widest mb-4">Modifier le mot de passe</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-xs text-luxury-muted dark:text-slate-400 mb-2">Nouveau mot de passe</label>
                            <input type="password" name="password" id="password" placeholder="Laisser vide pour ne pas changer"
                                   class="w-full bg-luxury-surface2 dark:bg-luxury-surface2/50 border border-luxury-borderSoft dark:border-luxury-borderSoft/50 rounded-xl px-4 py-3 text-white text-white focus:outline-none focus:ring-2 focus:ring-gold-primary/50/50 focus:border-gold-primary/50/50 transition-all text-sm @error('password') border-red-400 @enderror">
                            @error('password')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs text-luxury-muted dark:text-slate-400 mb-2">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirmer si modifié"
                                   class="w-full bg-luxury-surface2 dark:bg-luxury-surface2/50 border border-luxury-borderSoft dark:border-luxury-borderSoft/50 rounded-xl px-4 py-3 text-white text-white focus:outline-none focus:ring-2 focus:ring-gold-primary/50/50 focus:border-gold-primary/50/50 transition-all text-sm">
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-gold-primary to-gold-soft text-black hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-gold-primary/25 hover:shadow-gold-primary/40 hover:scale-[1.02] transition-all duration-300 flex items-center gap-2">
                        <i data-lucide="circle" class=" inline-block"></i>Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="fixed bottom-6 right-6 z-50">
    <div class="bg-emerald-500 text-white px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 font-bold text-sm">
        <i data-lucide="check-circle" class="text-lg inline-block"></i>
        Profil mis à jour avec succès !
    </div>
</div>
@endif
@endsection
