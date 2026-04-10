@extends('layouts.app')

@section('title', 'Tableau de bord Étudiant')

@section('content')

<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <i class="bi bi-mortarboard text-indigo-600 mr-3"></i>Espace Étudiant
        </h2>
        <p class="mt-1 text-sm text-gray-500">Gérez vos candidatures de stage et trouvez l'entreprise idéale.</p>
    </div>
    <a href="{{ route('student.offres.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-md transition">
        <i class="bi bi-search mr-2"></i>Chercher un stage
    </a>
</div>

{{-- KPI Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center">
        <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-lg mb-2"><i class="bi bi-send-fill"></i></div>
        <p class="text-xs font-medium text-gray-500 uppercase">Candidatures envoyées</p>
        <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $total_candidatures }}</h3>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-emerald-500/20 p-6 flex flex-col items-center justify-center bg-emerald-50">
        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg mb-2"><i class="bi bi-check-circle-fill"></i></div>
        <p class="text-xs font-medium text-emerald-600 uppercase">Acceptées</p>
        <h3 class="text-2xl font-bold text-emerald-700 mt-1">{{ $accepted }}</h3>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-amber-500/20 p-6 flex flex-col items-center justify-center bg-amber-50">
        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-lg mb-2"><i class="bi bi-hourglass-split"></i></div>
        <p class="text-xs font-medium text-amber-600 uppercase">En attente</p>
        <h3 class="text-2xl font-bold text-amber-700 mt-1">{{ $pending }}</h3>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-red-500/20 p-6 flex flex-col items-center justify-center bg-red-50">
        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-lg mb-2"><i class="bi bi-x-circle-fill"></i></div>
        <p class="text-xs font-medium text-red-600 uppercase">Refusées</p>
        <h3 class="text-2xl font-bold text-red-700 mt-1">{{ $rejected }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    
    {{-- Recent Applications (Table takes up more space) --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Vos dernières candidatures</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase rounded-tl-lg">Offre</th>
                        <th class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase">Entreprise</th>
                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-3 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase rounded-tr-lg">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($candidatures as $candidature)
                        <tr>
                            <td class="px-3 py-3 text-sm text-gray-900 font-medium">
                                {{ $candidature->offre->titre }}
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-500">
                                {{ $candidature->offre->entreprise->name ?? 'N/A' }}
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($candidature->statut->value === 'en attente')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        {{ $candidature->statut->label() }}
                                    </span>
                                @elseif($candidature->statut->value === 'acceptée')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        {{ $candidature->statut->label() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ $candidature->statut->label() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-500 text-right">
                                {{ $candidature->date_candidature ? \Carbon\Carbon::parse($candidature->date_candidature)->format('d M') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-6 text-center text-sm text-gray-500">
                                Vous n'avez envoyé aucune candidature pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-right">
            <a href="{{ route('student.candidatures.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Voir tout &rarr;</a>
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Aperçu global</h3>
        <div class="flex-grow flex items-center justify-center">
            @if($total_candidatures > 0)
                <div class="h-48 w-full">
                    <canvas id="studentChart"></canvas>
                </div>
            @else
                <p class="text-sm text-gray-400 text-center">Pas assez de données pour générer le graphique.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
@if($total_candidatures > 0)
<script type="module">
    import Chart from 'chart.js/auto';

    const ctx = document.getElementById('studentChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                data: {!! json_encode($chartData) !!},
                backgroundColor: [
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(239, 68, 68, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
@endif
@endpush

@endsection
