@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <i class="bi bi-speedometer2 text-indigo-600 mr-3"></i>Dashboard Administrateur
        </h2>
        <p class="mt-1 text-sm text-gray-500">Vue globale des statistiques de la plateforme GestionStages.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.export.users') }}" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-lg shadow-sm transition flex items-center">
            <i class="bi bi-file-earmark-pdf text-red-500 mr-2"></i>Export Utilisateurs
        </a>
        <a href="{{ route('admin.export.offres') }}" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-lg shadow-sm transition flex items-center">
            <i class="bi bi-file-earmark-pdf text-red-500 mr-2"></i>Export Offres
        </a>
        <a href="{{ route('admin.export.candidatures') }}" class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-lg shadow-sm transition flex items-center">
            <i class="bi bi-file-earmark-pdf text-red-500 mr-2"></i>Export Candidatures
        </a>
    </div>
</div>

{{-- Top KPI Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Users --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-xl mb-4">
            <i class="bi bi-people-fill"></i>
        </div>
        <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Utilisateurs</p>
        <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $total_users }}</h3>
        <p class="text-xs text-gray-400 mt-2">{{ $total_students }} étudiants &bull; {{ $total_entreprises }} entreprises</p>
    </div>

    {{-- Offres --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-xl mb-4">
            <i class="bi bi-briefcase-fill"></i>
        </div>
        <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Offres Publiées</p>
        <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $total_offres }}</h3>
    </div>

    {{-- Candidatures --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col items-center justify-center hover:shadow-md transition">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-xl mb-4">
            <i class="bi bi-file-earmark-check-fill"></i>
        </div>
        <p class="text-sm font-medium text-gray-500 uppercase tracking-widest">Candidatures</p>
        <h3 class="text-3xl font-bold text-gray-900 mt-1">{{ $total_candidatures }}</h3>
    </div>

    {{-- Accepted (Focus KPI) --}}
    <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl shadow-md border border-indigo-500 p-6 flex flex-col items-center justify-center text-white hover:shadow-lg transition transform hover:-translate-y-1">
        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center text-xl mb-4">
            <i class="bi bi-award-fill"></i>
        </div>
        <p class="text-sm font-medium text-indigo-100 uppercase tracking-widest">Stages Validés</p>
        <h3 class="text-3xl font-bold text-white mt-1">{{ $accepted_candidatures }}</h3>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    {{-- Chart --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Répartition des Candidatures</h3>
        <div class="h-64 flex items-center justify-center w-full">
            <canvas id="candidaturesChart"></canvas>
        </div>
    </div>

    {{-- Recent Users Data --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Nouveaux Utilisateurs</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($recent_users as $ru)
                    <tr>
                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ $ru->name }}<br><span class="text-xs text-gray-500">{{ $ru->email }}</span>
                        </td>
                        <td class="px-3 py-3 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                {{ $ru->role->label() }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="px-3 py-4 text-center text-sm text-gray-500">Aucun utilisateur récent</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script type="module">
    import Chart from 'chart.js/auto';

    const ctx = document.getElementById('candidaturesChart').getContext('2d');
    const bgColors = ['rgb(245, 158, 11)', 'rgb(16, 185, 129)', 'rgb(239, 68, 68)'];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                data: {!! json_encode($chartData) !!},
                backgroundColor: bgColors,
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '70%'
        }
    });
</script>
@endpush

@endsection
