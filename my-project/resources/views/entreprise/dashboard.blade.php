@extends('layouts.app')

@section('title', 'Tableau de bord Entreprise')

@section('content')

<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <i class="bi bi-building text-amber-500 mr-3"></i>Espace Entreprise
        </h2>
        <p class="mt-1 text-sm text-gray-500">Gérez vos offres de stage et analysez l'impact de vos recrutements.</p>
    </div>
    <a href="{{ route('entreprise.offres.create') }}" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-lg shadow-md transition">
        <i class="bi bi-plus-circle mr-2"></i>Publier une offre
    </a>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center">
        <p class="text-sm font-medium text-gray-500 uppercase">Offres actives</p>
        <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $total_offres }}</h3>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center">
        <p class="text-sm font-medium text-gray-500 uppercase">Candidatures reçues</p>
        <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $total_candidatures }}</h3>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-emerald-500/20 p-6 flex flex-col items-center justify-center bg-emerald-50/50">
        <p class="text-sm font-medium text-emerald-600 uppercase">Acceptées</p>
        <h3 class="text-3xl font-bold text-emerald-700 mt-2">{{ $accepted }}</h3>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-amber-500/20 p-6 flex flex-col items-center justify-center bg-amber-50/50">
        <p class="text-sm font-medium text-amber-600 uppercase">En attente</p>
        <h3 class="text-3xl font-bold text-amber-700 mt-2">{{ $pending }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- Chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Entonnoir de recrutement</h3>
        <div class="h-72 w-full">
            <canvas id="entrepriseChart"></canvas>
        </div>
    </div>

    {{-- Recent Offers --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Dernières Offres Publiées</h3>
        <ul class="divide-y divide-gray-100">
            @forelse($offres as $offre)
                <li class="py-3 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $offre->titre }}</p>
                        <p class="text-xs text-gray-500">{{ $offre->date_publication->format('d M Y') }}</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $offre->candidatures_count }} candidature(s)
                        </span>
                    </div>
                </li>
            @empty
                <li class="py-4 text-sm text-gray-500 text-center">Aucune offre publiée.</li>
            @endforelse
        </ul>
    </div>
</div>

@push('scripts')
<script type="module">
    import Chart from 'chart.js/auto';

    const ctx = document.getElementById('entrepriseChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Candidatures par statut',
                data: {!! json_encode($chartData) !!},
                backgroundColor: [
                    'rgba(245, 158, 11, 0.7)',
                    'rgba(16, 185, 129, 0.7)',
                    'rgba(239, 68, 68, 0.7)'
                ],
                borderColor: [
                    'rgb(245, 158, 11)',
                    'rgb(16, 185, 129)',
                    'rgb(239, 68, 68)'
                ],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush

@endsection
