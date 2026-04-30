@extends('layouts.app')

@section('title', 'Résultat AI Matching')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('student.match.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center">
        <i class="bi bi-arrow-left mr-1"></i> Retour aux offres
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="p-6 md:p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- Left Column: Score Circle --}}
            <div class="flex flex-col items-center justify-center border-r border-gray-100 md:pr-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Score de compatibilité</h2>
                
                @php
                    $scoreValue = round($match->score);
                    $colorHex = $scoreValue >= 70 ? '#10B981' : ($scoreValue >= 40 ? '#F59E0B' : '#EF4444');
                @endphp
                
                <div class="relative w-48 h-48">
                    <canvas id="scoreChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-4xl font-extrabold text-gray-900">{{ $scoreValue }}%</span>
                        <span class="text-sm font-medium mt-1 text-gray-500">{{ $match->matchLabel() }}</span>
                    </div>
                </div>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('student.cv.optimize', $offre->id) }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                        <i class="bi bi-magic mr-2"></i> Optimiser mon CV pour cette offre
                    </a>
                </div>
            </div>

            {{-- Right Column: Offre details & AI Summary --}}
            <div class="md:col-span-2 flex flex-col">
                <div class="mb-6">
                    <h1 class="text-2xl font-extrabold text-gray-900 mb-2">{{ $offre->titre }}</h1>
                    <div class="flex flex-wrap gap-3 mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="bi bi-building mr-1"></i> {{ $offre->entreprise->company_name ?? 'Entreprise' }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="bi bi-geo-alt mr-1"></i> {{ $offre->lieu ?? 'Non spécifié' }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                            <i class="bi bi-tag mr-1"></i> {{ $offre->type ?? 'Stage' }}
                        </span>
                    </div>
                </div>

                @if(!empty($match->details['boost_applied']))
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($match->details['boost_applied'] as $boost)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black bg-indigo-600 text-white shadow-sm">
                                <i class="bi bi-lightning-fill mr-1 text-yellow-300"></i> {{ mb_strtoupper($boost) }}
                            </span>
                        @endforeach
                    </div>
                @endif

                <div class="bg-gray-50 p-5 rounded-lg border border-gray-100 mb-6 flex-grow">
                    <h3 class="flex items-center font-bold text-gray-800 mb-3">
                        <i class="bi bi-robot text-indigo-500 mr-2 text-xl"></i> Analyse de l'IA
                    </h3>
                    <p class="text-gray-700 italic leading-relaxed">
                        "{{ $match->details['ai_summary'] ?? 'Analyse générale de votre profil comparé à cette offre.' }}"
                    </p>
                </div>
            </div>
            
        </div>
    </div>
</div>

{{-- Breakdown Section --}}
<h2 class="text-xl font-bold text-gray-900 mb-4 border-b border-gray-200 pb-2">Détails de l'évaluation</h2>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
@foreach([
        ['key' => 'skills', 'label' => 'Compétences Clés', 'icon' => 'bi-code-slash', 'color' => 'text-blue-500', 'max' => 40],
        ['key' => 'experience', 'label' => 'Pertinence Expérience', 'icon' => 'bi-briefcase', 'color' => 'text-purple-500', 'max' => 25],
        ['key' => 'keywords', 'label' => 'Mots-clés & Intention', 'icon' => 'bi-search', 'color' => 'text-yellow-500', 'max' => 15],
        ['key' => 'level', 'label' => 'Niveau d\'études', 'icon' => 'bi-mortarboard', 'color' => 'text-green-500', 'max' => 10],
        ['key' => 'tools', 'label' => 'Outils & Technologies', 'icon' => 'bi-tools', 'color' => 'text-indigo-500', 'max' => 10]
    ] as $crit)
        @php
            $data = $match->details[$crit['key']] ?? null;
            if(!$data) continue;
            
            $subScore = $data['score'] ?? 0;
            $max = $crit['max'];
            $percent = $max > 0 ? ($subScore / $max) * 100 : 0;
            $barColor = $percent >= 75 ? 'bg-green-500' : ($percent >= 40 ? 'bg-yellow-400' : 'bg-red-500');
        @endphp
        
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col">
            <div class="flex justify-between items-center mb-3">
                <h4 class="font-bold text-gray-800 flex items-center">
                    <i class="bi {{ $crit['icon'] }} {{ $crit['color'] }} mr-2"></i> {{ $crit['label'] }}
                </h4>
                <span class="text-sm font-bold text-gray-600">{{ round($subScore, 1) }} / {{ $max }}</span>
            </div>
            
            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
            </div>
            
            <p class="text-sm text-gray-600 flex-grow">{{ $data['reason'] ?? '' }}</p>

            @if($crit['key'] === 'skills' && !empty($data['matched']))
                <div class="mt-3">
                    <strong class="text-xs text-green-700 block mb-1">✓ Match :</strong>
                    <div class="flex flex-wrap gap-1">
                        @foreach($data['matched'] as $s)
                            <span class="text-[10px] bg-green-50 text-green-700 px-2 py-0.5 rounded border border-green-200 font-bold uppercase">{{ $s }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
            
            @if($crit['key'] === 'skills' && !empty($data['missing']))
                <div class="mt-3">
                    <strong class="text-xs text-red-700 block mb-1">✗ Manquants :</strong>
                    <div class="flex flex-wrap gap-1">
                        @foreach($data['missing'] as $s)
                            <span class="text-[10px] bg-red-50 text-red-700 px-2 py-0.5 rounded border border-red-200 font-bold uppercase">{{ $s }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('scoreChart').getContext('2d');
        var scoreValue = {{ round($match->score) }};
        var color = '{{ $colorHex }}';
        var remainder = 100 - scoreValue;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [scoreValue, remainder],
                    backgroundColor: [color, '#f3f4f6'],
                    borderWidth: 0,
                    borderRadius: 5,
                    cutout: '80%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                plugins: {
                    tooltip: { enabled: false },
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endpush
@endsection
