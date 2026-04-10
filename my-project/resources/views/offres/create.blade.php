@extends('layouts.app')

@section('title', 'Créer une Offre')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 flex items-center">
                <i class="bi bi-plus-circle-fill text-indigo-600 mr-3"></i>Nouvelle Offre de Stage
            </h2>
            <p class="mt-1 text-sm text-gray-500">Remplissez les informations ci-dessous pour publier une nouvelle opportunité.</p>
        </div>
        <div>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.offres.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <i class="bi bi-arrow-left mr-2"></i>Retour
                </a>
            @elseif(auth()->user()->isEntreprise())
                <a href="{{ route('entreprise.offres.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <i class="bi bi-arrow-left mr-2"></i>Retour
                </a>
            @endif
        </div>
    </div>

    {{-- Card Form --}}
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-6 sm:p-8">
            
            @if(auth()->user()->isAdmin())
                <form action="{{ route('admin.offres.store') }}" method="POST" class="space-y-6">
            @else
                <form action="{{ route('entreprise.offres.store') }}" method="POST" class="space-y-6">
            @endif
                @csrf

                {{-- Titre --}}
                <div>
                    <label for="titre" class="block text-sm font-semibold text-gray-700 mb-1">Titre de l'offre <span class="text-red-500">*</span></label>
                    <input type="text" name="titre" id="titre"
                           placeholder="Ex: Développeur Fullstack Laravel, Stage PFE..."
                           value="{{ old('titre') }}" 
                           required autofocus
                           class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors {{ $errors->has('titre') ? 'border-red-300 focus:border-red-500 bg-red-50' : 'border-gray-300' }}">
                    @error('titre')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="5"
                              placeholder="Détaillez les missions, le profil recherché, etc."
                              required
                              class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors {{ $errors->has('description') ? 'border-red-300 focus:border-red-500 bg-red-50' : 'border-gray-300' }}">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Lieu --}}
                    <div>
                        <label for="lieu" class="block text-sm font-semibold text-gray-700 mb-1">Lieu (Localisation)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-geo-alt text-gray-400"></i>
                            </div>
                            <input type="text" name="lieu" id="lieu"
                                   placeholder="Ex: Paris, Télétravail"
                                   value="{{ old('lieu') }}" 
                                   class="w-full pl-10 px-4 py-3 rounded-lg border focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors {{ $errors->has('lieu') ? 'border-red-300 focus:border-red-500 bg-red-50' : 'border-gray-300' }}">
                        </div>
                        @error('lieu')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Date de publication --}}
                    <div>
                        <label for="date_publication" class="block text-sm font-semibold text-gray-700 mb-1">Date de publication <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-calendar-date text-gray-400"></i>
                            </div>
                            <input type="date" name="date_publication" id="date_publication"
                                   value="{{ old('date_publication', date('Y-m-d')) }}" 
                                   required
                                   class="w-full pl-10 px-4 py-3 rounded-lg border focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors {{ $errors->has('date_publication') ? 'border-red-300 focus:border-red-500 bg-red-50' : 'border-gray-300' }}">
                        </div>
                        @error('date_publication')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- Entreprise (visible uniquement pour l'admin) --}}
                @if(auth()->user()->isAdmin())
                <div class="pt-4 border-t border-gray-100">
                    <label for="entreprise_id" class="block text-sm font-semibold text-gray-700 mb-1">Entreprise associée <span class="text-red-500">*</span></label>
                    <select name="entreprise_id" id="entreprise_id" required
                            class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors {{ $errors->has('entreprise_id') ? 'border-red-300 focus:border-red-500 bg-red-50' : 'border-gray-300' }}">
                        <option value="">-- Sélectionner l'entreprise --</option>
                        @foreach($entreprises as $entreprise)
                            <option value="{{ $entreprise->id }}" {{ old('entreprise_id') == $entreprise->id ? 'selected' : '' }}>
                                {{ $entreprise->name }} {{ $entreprise->company_name ? '(' . $entreprise->company_name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('entreprise_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="bi bi-exclamation-circle-fill mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
                @endif

                {{-- Bouton Submit --}}
                <div class="pt-6">
                    <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 rounded-xl shadow-lg text-white font-bold bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all hover:-translate-y-0.5">
                        <i class="bi bi-send-fill mr-2"></i>Publier l'offre
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
