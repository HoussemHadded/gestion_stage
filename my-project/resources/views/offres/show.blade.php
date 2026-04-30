@extends('layouts.app')

@section('title', $offre->titre)

@section('content')

<div class="mb-6">
    @php
        $backRoute = route('student.offres.index');
        if(auth()->user()->isAdmin()) $backRoute = route('admin.offres.index');
        if(auth()->user()->isEntreprise()) $backRoute = route('entreprise.offres.index');
    @endphp
    <a href="{{ $backRoute }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600 flex items-center transition">
        <i class="bi bi-arrow-left mr-2"></i>Retour aux offres
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    
    {{-- Main Offer Details --}}
    <div class="lg:col-span-2">
        <div class="glass-card rounded-3xl p-8 mb-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-50 to-blue-50 text-indigo-600 flex items-center justify-center text-3xl rounded-2xl shadow-sm border border-indigo-100">
                    <i class="bi bi-building"></i>
                </div>
            </div>
            
            <div class="pr-20">
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-extrabold bg-indigo-50 text-indigo-600 border border-indigo-100 mb-4 tracking-wider">
                    {{ mb_strtoupper($offre->type ?? 'STAGE') }}
                </span>
                
                <h1 class="text-4xl font-black text-gray-900 mb-2 leading-tight">{{ $offre->titre }}</h1>
                <p class="text-lg font-bold text-gray-500 mb-6 flex items-center">
                    {{ $offre->entreprise->name ?? 'Entreprise' }}
                    @if($offre->lieu)
                        <span class="text-gray-300 mx-3">•</span> <i class="bi bi-geo-alt-fill text-gray-400 mr-1.5 pt-0.5 text-sm"></i> {{ $offre->lieu }}
                    @endif
                </p>
                
                <div class="flex flex-wrap gap-4 border-y border-gray-100/80 py-4 mb-6">
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Publié le</p>
                        <p class="text-sm font-bold text-gray-900">{{ $offre->date_publication ? \Carbon\Carbon::parse($offre->date_publication)->format('d M Y') : 'N/A' }}</p>
                    </div>
                    @if($offre->level_required)
                    <div class="border-l border-gray-100 pl-4">
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Niveau d'étude</p>
                        <p class="text-sm font-bold text-gray-900">{{ $offre->level_required }}</p>
                    </div>
                    @endif
                </div>

                <div class="prose prose-indigo max-w-none text-gray-600 text-sm leading-relaxed">
                    <h3 class="text-lg font-bold text-gray-900 mb-3 block">Description de l'offre</h3>
                    <p class="whitespace-pre-line">{{ $offre->description }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar: Match AI & One-Click Apply (Only for Students) --}}
    <div class="space-y-6">
        
        @if(auth()->user()->isEtudiant())
            {{-- AI Match Card --}}
            <div class="bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-900 via-indigo-950 to-gray-900 rounded-3xl p-6 shadow-[0_20px_50px_rgb(0,0,0,0.3)] text-white relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-48 h-48 bg-indigo-500 rounded-full blur-[60px] opacity-20"></div>
                
                <h3 class="text-lg font-extrabold flex items-center tracking-wide mb-6">
                    <i class="bi bi-robot mr-2 text-indigo-400"></i>AI Match Score
                </h3>

                @if($matchScore)
                    <div class="flex justify-center mb-6 relative">
                        <svg class="transform -rotate-90 w-32 h-32" viewBox="0 0 100 100">
                            <!-- Background circle -->
                            <circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.1)" stroke-width="8" fill="none" />
                            <!-- Progress circle -->
                            @php 
                                $circumference = 2 * pi() * 40; 
                                $offset = $circumference - ($matchScore->score / 100) * $circumference;
                                $strokeColor = $matchScore->score >= 80 ? '#10b981' : ($matchScore->score >= 50 ? '#f59e0b' : '#ef4444');
                            @endphp
                            <circle cx="50" cy="50" r="40" stroke="{{ $strokeColor }}" stroke-width="8" fill="none" stroke-linecap="round" 
                                    style="stroke-dasharray: {{ $circumference }}; stroke-dashoffset: {{ $offset }}; transition: stroke-dashoffset 1.5s ease-out;" />
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-3xl font-black">{{ round($matchScore->score) }}</span>
                            <span class="text-[10px] font-bold text-indigo-300 uppercase">/ 100</span>
                        </div>
                    </div>
                    
                    <p class="text-xs text-indigo-200 text-center mb-6 leading-relaxed bg-black/20 p-3 rounded-xl border border-white/5">
                        @if($matchScore->score >= 80)
                            🎯 Excellent profil ! Vous avez de très fortes chances d'être sélectionné.
                        @elseif($matchScore->score >= 50)
                            👍 Profil compatible. Optimisez votre CV pour maximiser vos chances !
                        @else
                            ⚠️ Match faible. Des compétences clés semblent manquer.
                        @endif
                    </p>
                    
                    <form action="{{ route('student.apply.optimized', $offre->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center py-3.5 px-4 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white font-extrabold rounded-xl shadow-[0_0_20px_rgb(99,102,241,0.4)] transition transform premium-hover">
                            <i class="bi bi-lightning-charge-fill mr-2 text-yellow-300"></i>⚡ IA Clic-Apply
                        </button>
                    </form>
                @else
                    <div class="text-center bg-black/20 p-5 rounded-2xl border border-dashed border-indigo-500/30 mb-6">
                        <div class="w-12 h-12 bg-indigo-500/20 text-indigo-400 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="bi bi-radar text-xl"></i>
                        </div>
                        <p class="text-xs text-indigo-200 mb-4 font-medium">Découvrez si votre profil correspond aux attentes de l'entreprise avant de postuler.</p>
                        <a href="{{ route('student.match.calculate', $offre->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-500/20 hover:bg-indigo-500/40 text-indigo-300 text-[11px] font-extrabold tracking-widest uppercase rounded-lg border border-indigo-400/30 transition">
                            Générer Score IA
                        </a>
                    </div>

                    {{-- Standard One-Click Apply inside AI card if no score yet --}}
                    <form action="{{ route('student.offres.postuler', $offre->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center py-3.5 px-4 bg-white hover:bg-gray-50 text-indigo-900 font-extrabold rounded-xl shadow-md transition premium-hover">
                            Postuler maintenant
                        </button>
                    </form>
                @endif
            </div>

            {{-- Actions --}}
            <div class="glass-card rounded-3xl p-6 flex flex-col space-y-3" x-data="{ saving: false, isSaved: {{ $isSaved ? 'true' : 'false' }} }">
                <button @click="
                            saving = true;
                            fetch('{{ route('student.offres.save', $offre->id) }}', {
                                method: 'POST',
                                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json'}
                            })
                            .then(res => res.json())
                            .then(data => {
                                saving = false;
                                isSaved = (data.status === 'saved');
                            });
                        " 
                        class="w-full flex items-center justify-center py-3 px-4 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition"
                        :class="isSaved ? 'border-red-200 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700' : ''">
                    
                    <i class="mr-2" :class="isSaved ? 'bi bi-heart-fill' : 'bi bi-heart'"></i>
                    <span x-text="isSaved ? 'Retirer des favoris' : 'Sauvegarder l\'offre'"></span>
                </button>
            </div>
        @else
            {{-- Admin/Entreprise specific sidebar actions if any --}}
            <div class="glass-card rounded-3xl p-6">
                <h4 class="text-sm font-bold text-gray-900 mb-4">Gestion de l'offre</h4>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.offres.edit', $offre->id) }}" class="w-full flex items-center justify-center py-3 px-4 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition mb-3">
                        <i class="bi bi-pencil-square mr-2"></i> Modifier (Admin)
                    </a>
                @elseif(auth()->user()->isEntreprise() && $offre->entreprise_id == auth()->id())
                    <a href="{{ route('entreprise.offres.edit', $offre->id) }}" class="w-full flex items-center justify-center py-3 px-4 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition mb-3">
                        <i class="bi bi-pencil-square mr-2"></i> Modifier l'offre
                    </a>
                @endif
                <p class="text-[11px] text-gray-400 text-center italic">Cette page affiche l'aperçu public de l'offre tel que les étudiants la voient.</p>
            </div>
        @endif
        
    </div>
</div>
@endsection
