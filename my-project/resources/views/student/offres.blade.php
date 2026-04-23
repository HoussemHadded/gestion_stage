@extends('layouts.app')

@section('title', 'Offres de Stage')

@section('content')

<div x-data="{ saving: [] }" class="mb-8">
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center">
                <i class="bi bi-briefcase-fill text-indigo-600 mr-3"></i>Offres de Stage
            </h2>
            <p class="mt-2 text-sm text-gray-500 font-medium">Recherche avancée pour trouver le stage idéal.</p>
        </div>
    </div>

    {{-- Advanced Search Bar --}}
    <div class="glass-card rounded-2xl p-4 mb-8 shadow-sm">
        <form action="{{ route('student.offres.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-search text-gray-400"></i>
                </div>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Mot-clé, titre, compétence..." class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl py-3 pl-10 pr-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
            </div>
            
            <div class="w-full md:w-64 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="bi bi-geo-alt text-gray-400"></i>
                </div>
                <input type="text" name="lieu" value="{{ request('lieu') }}" placeholder="Ville, région..." class="w-full bg-white border border-gray-200 text-gray-900 rounded-xl py-3 pl-10 pr-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
            </div>

            <div class="w-full md:w-48">
                <select name="type" class="w-full bg-white border border-gray-200 text-gray-600 rounded-xl py-3 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                    <option value="">Tous types</option>
                    <option value="Stage PFE" {{ request('type') == 'Stage PFE' ? 'selected' : '' }}>Stage PFE</option>
                    <option value="Stage d'été" {{ request('type') == "Stage d'été" ? 'selected' : '' }}>Stage d'été</option>
                    <option value="Alternance" {{ request('type') == 'Alternance' ? 'selected' : '' }}>Alternance</option>
                </select>
            </div>

            <button type="submit" class="px-6 py-3 bg-gradient-to-tr from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-md transition transform premium-hover flex items-center justify-center">
                Rechercher
            </button>
            @if(request()->hasAny(['q', 'lieu', 'type']))
                <a href="{{ route('student.offres.index') }}" class="px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold rounded-xl transition flex items-center justify-center">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
        </form>
    </div>

    @if($offres->isEmpty())
        <div class="glass-card rounded-3xl p-16 text-center border-dashed border-2 border-gray-200">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-sm">
                <i class="bi bi-search text-3xl text-gray-300"></i>
            </div>
            <h5 class="text-xl font-bold text-gray-900 mb-2">Aucune offre ne correspond à vos critères</h5>
            <p class="text-gray-500 max-w-md mx-auto">Essayez de modifier vos filtres ou de chercher avec des mots-clés plus génériques.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($offres as $offre)
                <div class="glass-card rounded-2xl p-6 flex flex-col premium-hover relative group border border-gray-100/80 shadow-sm">
                    {{-- Toggle Save Actions --}}
                    @php $isSaved = in_array($offre->id, $savedOffresIds ?? []); @endphp
                    <button @click="
                                saving.push({{ $offre->id }});
                                fetch('{{ route('student.offres.save', $offre->id) }}', {
                                    method: 'POST',
                                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
                                })
                                .then(res => res.json())
                                .then(data => {
                                    saving = saving.filter(id => id !== {{ $offre->id }});
                                    if(data.status === 'saved') $el.classList.add('text-red-500');
                                    else $el.classList.remove('text-red-500');
                                    $el.querySelector('i').className = data.status === 'saved' ? 'bi bi-heart-fill' : 'bi bi-heart';
                                });
                            " 
                            class="absolute top-4 right-4 w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors z-10 border border-gray-100 focus:outline-none {{ $isSaved ? 'text-red-500' : '' }}">
                        <i class="bi {{ $isSaved ? 'bi-heart-fill' : 'bi-heart' }} text-lg transition-transform transform group-hover:scale-110"></i>
                    </button>

                    <div class="flex items-start mb-4 pr-10">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-50 to-blue-50 text-indigo-600 flex items-center justify-center text-xl mr-4 border border-indigo-100 shadow-sm">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <h6 class="font-extrabold text-gray-900 leading-tight mb-1 group-hover:text-indigo-600 transition">{{ $offre->titre }}</h6>
                            <p class="text-xs font-bold text-gray-500 flex items-center tracking-wide">
                                {{ mb_strtoupper($offre->entreprise->name ?? 'Entreprise') }}
                            </p>
                        </div>
                    </div>

                    <p class="text-gray-500 text-sm mb-4 flex-grow line-clamp-3 leading-relaxed">
                        {{ $offre->description }}
                    </p>

                    <div class="flex flex-wrap gap-2 mb-6">
                        @if($offre->lieu)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-extrabold bg-gray-100 text-gray-600 border border-gray-200">
                                <i class="bi bi-geo-alt mr-1"></i>{{ mb_strtoupper($offre->lieu) }}
                            </span>
                        @endif
                        @if($offre->type)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-extrabold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                {{ mb_strtoupper($offre->type) }}
                            </span>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[10px] font-extrabold text-gray-400 flex items-center">
                            IL Y A {{ $offre->date_publication ? \Carbon\Carbon::parse($offre->date_publication)->diffForHumans(null, true) : '...' }}
                        </span>
                        
                        <a href="{{ route('student.offres.show', $offre->id) }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-purple-600 transition cursor-pointer">
                            Voir l'offre <i class="bi bi-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8 flex justify-center">
            {{ $offres->links() }}
        </div>
    @endif
</div>
@endsection
