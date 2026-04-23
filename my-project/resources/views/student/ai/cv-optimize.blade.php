@extends('layouts.app')

@section('title', 'Optimisation du CV')

@section('content')
<div class="mb-4">
    <a href="{{ route('student.match.calculate', $offre->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center">
        <i class="bi bi-arrow-left mr-1"></i> Retour au résultat du match
    </a>
</div>

<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 border-b-2 border-indigo-500 pb-2 inline-block">
        <i class="bi bi-magic text-indigo-600 mr-2"></i>Optimisation IA de votre CV
    </h1>
    <p class="text-gray-500 mt-2">
        Conseils sur-mesure pour adapter votre profil à l'offre : 
        <strong class="text-gray-800">{{ $offre->titre }}</strong>
    </p>
</div>

{{-- Top section: Suggestions & Missing skills --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    {{-- Missing Skills --}}
    <div class="bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">
        <div class="bg-red-50 border-b border-red-100 px-6 py-4 flex items-center">
            <i class="bi bi-exclamation-octagon text-red-500 mr-2 text-xl"></i>
            <h3 class="font-bold text-red-800">Compétences manquantes</h3>
        </div>
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">L'IA a identifié ces compétences clés demandées par l'offre mais absentes de votre CV actuel :</p>
            
            @if(empty($optimization['missing_skills']))
                <div class="flex items-center text-green-600 font-medium bg-green-50 p-3 rounded">
                    <i class="bi bi-check-circle-fill mr-2"></i> Aucune lacune majeure détectée !
                </div>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($optimization['missing_skills'] as $skill)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                            <i class="bi bi-x mr-1"></i> {{ $skill }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Actionable Suggestions --}}
    <div class="bg-white rounded-xl shadow-sm border border-blue-100 overflow-hidden">
        <div class="bg-blue-50 border-b border-blue-100 px-6 py-4 flex items-center">
            <i class="bi bi-lightbulb text-blue-500 mr-2 text-xl"></i>
            <h3 class="font-bold text-blue-800">Conseils d'amélioration</h3>
        </div>
        <div class="p-6">
            @if(empty($optimization['suggestions']))
                <p class="text-gray-500">Aucune suggestion spécifique générée.</p>
            @else
                <ul class="space-y-3">
                    @foreach($optimization['suggestions'] as $index => $suggestion)
                    <li class="flex items-start">
                        <span class="flex-shrink-0 h-6 w-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold mt-0.5 mr-3">
                            {{ $index + 1 }}
                        </span>
                        <span class="text-gray-700 text-sm leading-relaxed">{{ $suggestion }}</span>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

{{-- Bottom section: Summary comparison --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-gray-50 border-b border-gray-200 px-6 py-4">
        <h3 class="font-bold text-gray-800"><i class="bi bi-file-text mr-2"></i>Pitch / Phrase d'accroche</h3>
    </div>
    <div class="p-6">
        <p class="text-sm text-gray-500 mb-6">Ajoutez ou remplacez le paragraphe d'introduction de votre CV par cette proposition générée par l'IA, spécifiquement conçue pour attirer l'attention du recruteur sur cette offre.</p>
        
        <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-5 relative">
            <i class="bi bi-quote text-4xl text-indigo-200 absolute top-2 left-4"></i>
            <p class="text-gray-800 font-medium text-lg italic relative z-10 pl-6 leading-relaxed">
                "{{ $optimization['improved_summary'] ?? 'Résumé non disponible.' }}"
            </p>
        </div>
        
        <div class="mt-6 text-center flex flex-wrap justify-center gap-4">
             <form action="{{ route('student.offres.postuler', $offre->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded shadow transition">
                    <i class="bi bi-send mr-2"></i> Postuler avec CV normal
                </button>
            </form>
            
            <form action="{{ route('student.apply.optimized', $offre->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded shadow transition">
                    <i class="bi bi-rocket-takeoff mr-2"></i> 🚀 Appliquer avec le CV optimisé
                </button>
            </form>
            
            <a href="{{ route('student.cv.download', $offre->id) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded shadow transition flex items-center inline-flex">
                <i class="bi bi-file-pdf mr-2"></i> Télécharger CV (PDF)
            </a>
        </div>
    </div>
</div>

@endsection
