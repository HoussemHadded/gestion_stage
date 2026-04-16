@extends('layouts.app')

@section('title', 'AI Matching - Liste des offres')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 border-b-2 border-indigo-500 pb-2 inline-block">
            <i class="bi bi-robot text-indigo-600 mr-2"></i>AI Matching
        </h1>
        <p class="text-gray-500 mt-2">Découvrez les offres qui correspondent le mieux à votre profil.</p>
    </div>
    <a href="{{ route('student.cv.show') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded shadow-sm inline-flex items-center transition">
        <i class="bi bi-file-earmark-person mr-2"></i> Mettre à jour mon CV
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($offres as $offre)
        @php
            // Get existing match if already calculated
            $match = $offre->matches->first();
            $hasScore = !is_null($match);
            
            // UI Color mapping based on score
            $badgeClass = 'bg-gray-100 text-gray-800';
            $icon = 'bi-question-circle';
            
            if ($hasScore) {
                if ($match->score >= 70) {
                    $badgeClass = 'bg-green-100 text-green-800 border border-green-200';
                    $icon = 'bi-check-circle-fill text-green-600';
                } elseif ($match->score >= 40) {
                    $badgeClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                    $icon = 'bi-exclamation-circle-fill text-yellow-600';
                } else {
                    $badgeClass = 'bg-red-100 text-red-800 border border-red-200';
                    $icon = 'bi-x-circle-fill text-red-600';
                }
            }
        @endphp

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col hover:shadow-md transition">
            <div class="p-5 flex-grow">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $offre->titre }}</h3>
                    @if($hasScore)
                        <span class="inline-flex items-center justify-center font-bold px-2.5 py-1 rounded-full text-sm {{ $badgeClass }}">
                            {{ round($match->score) }}%
                        </span>
                    @else
                        <span class="inline-flex items-center justify-center font-medium px-2.5 py-1 rounded-full text-xs bg-gray-100 text-gray-500">
                            Non calculé
                        </span>
                    @endif
                </div>
                
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $offre->description }}</p>
                
                <div class="text-xs text-gray-500 space-y-1 mb-4">
                    <p><i class="bi bi-geo-alt mr-1"></i>{{ $offre->lieu ?? 'Lieu non spécifié' }}</p>
                    <p><i class="bi bi-tag mr-1"></i>{{ $offre->type ?? 'Type non spécifié' }}</p>
                    <p><i class="bi bi-mortarboard mr-1"></i>{{ $offre->level_required ?? 'Niveau non spécifié' }}</p>
                </div>
                
                @if($hasScore)
                    <div class="text-sm font-medium mb-2 flex items-center">
                        <i class="bi {{ $icon }} mr-2"></i>
                        <span class="text-gray-700">{{ $match->matchLabel() }}</span>
                    </div>
                @endif
            </div>
            
            <div class="bg-gray-50 px-5 py-3 border-t border-gray-100 flex justify-between items-center">
                <a href="{{ route('student.offres.show', $offre->id) }}" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                    Voir détails
                </a>
                
                <a href="{{ route('student.match.calculate', $offre->id) }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-indigo-50 border border-transparent rounded-md font-medium text-xs text-indigo-700 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    @if($hasScore)
                        <i class="bi bi-arrow-repeat mr-1"></i> Recalculer
                    @else
                        <i class="bi bi-calculator mr-1"></i> Calculer Match
                    @endif
                </a>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white p-8 rounded-lg shadow text-center text-gray-500">
            <i class="bi bi-folder-x text-4xl mb-3 block text-gray-300"></i>
            <p>Aucune offre de stage n'est actuellement disponible.</p>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $offres->links() }}
</div>
@endsection
