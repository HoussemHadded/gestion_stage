@extends('layouts.app')

@section('title', 'Tableau de bord Entreprise')

@section('content')

<div x-data="{ loaded: false }" x-init="setTimeout(() => { loaded = true; if(window.renderEntrepriseCharts) window.renderEntrepriseCharts(); }, 800)">

    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600 flex items-center">
                <i class="bi bi-bar-chart-line-fill text-amber-500 mr-3"></i>Insights Entreprise
            </h2>
            <p class="mt-2 text-sm text-gray-500 font-medium">Analysez l'impact de vos offres et la vélocité de vos recrutements.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('entreprise.candidatures.kanban') }}" class="px-5 py-3 glass-card hover:bg-gray-50 text-gray-700 text-sm font-bold rounded-xl shadow-sm transition transform hover:-translate-y-1 premium-hover flex items-center">
                <i class="bi bi-kanban mr-2 text-indigo-500"></i>Mode Pipeline
            </a>
            <a href="{{ route('entreprise.offres.create') }}" class="px-6 py-3 bg-gradient-to-tr from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white text-sm font-bold rounded-xl shadow-[0_8px_20px_rgb(245,158,11,0.3)] transition transform hover:-translate-y-1 premium-hover flex items-center">
                <i class="bi bi-plus-lg mr-2"></i>Publier
            </a>
        </div>
    </div>

    {{-- Skeleton Loaders --}}
    <div x-show="!loaded" class="flex flex-col space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <template x-for="i in 4" :key="i">
                <div class="h-40 rounded-2xl sk-loading w-full"></div>
            </template>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 h-96 rounded-2xl sk-loading w-full"></div>
            <div class="h-96 rounded-2xl sk-loading w-full"></div>
        </div>
    </div>

    {{-- Loaded Content --}}
    <div x-show="loaded" style="display: none;" x-transition:enter="transition ease-out duration-700" opacity-0 transform translate-y-4 opacity-100 translate-y-0>
        
        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass-card rounded-2xl p-6 flex flex-col premium-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest relative z-10 flex items-center"><i class="bi bi-briefcase-fill mr-1.5 text-indigo-500"></i>Offres actives</p>
                <h3 class="text-4xl font-extrabold text-gray-900 mt-3 relative z-10">{{ $total_offres }}</h3>
                <p class="text-xs text-gray-500 mt-1font-medium relative z-10 border-t border-gray-100 pt-3 mt-4">En ligne actuellement</p>
            </div>
            
            <div class="glass-card rounded-2xl p-6 flex flex-col premium-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest relative z-10 flex items-center"><i class="bi bi-inboxes-fill mr-1.5 text-blue-500"></i>Volume Candidats</p>
                <h3 class="text-4xl font-extrabold text-gray-900 mt-3 relative z-10">{{ $total_candidatures }}</h3>
                <p class="text-xs text-gray-500 mt-1font-medium relative z-10 border-t border-gray-100 pt-3 mt-4">Toutes offres confondues</p>
            </div>

            <div class="glass-card rounded-2xl p-6 flex flex-col premium-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest relative z-10 flex items-center"><i class="bi bi-lightning-charge-fill mr-1.5 text-emerald-500"></i>Taux de Conversion</p>
                <div class="flex items-end mt-3 relative z-10">
                    <h3 class="text-4xl font-extrabold text-gray-900">{{ round($conversion_rate) }}</h3>
                    <span class="text-sm font-bold text-gray-500 ml-1 mb-1">%</span>
                </div>
                <p class="text-xs text-gray-500 mt-1font-medium relative z-10 border-t border-gray-100 pt-3 mt-4">{{ $accepted }} candidat(s) recruté(s)</p>
            </div>

            <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl shadow-lg border border-amber-400 p-6 flex flex-col text-white premium-hover relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/20 rounded-full"></div>
                <p class="text-[10px] font-extrabold text-amber-100 uppercase tracking-widest relative z-10 flex items-center"><i class="bi bi-funnel-fill mr-1.5"></i>Tension Pipeline</p>
                <h3 class="text-4xl font-extrabold mt-3 relative z-10">{{ $pending + $shortlisted + $interview }}</h3>
                <div class="mt-4 flex items-center text-xs font-bold text-amber-50 relative z-10 bg-black/10 w-max px-2 py-1 rounded">
                    Mouvements en cours
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 glass-card rounded-3xl p-8">
                <div class="flex justify-between items-center border-b border-gray-100/50 pb-5 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Macro-Pipeline de Recrutement</h3>
                    <div class="p-2 bg-indigo-50 rounded-lg text-indigo-500"><i class="bi bi-bar-chart-fill"></i></div>
                </div>
                <div class="chart-container">
                    <canvas id="entrepriseChart"></canvas>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-8 flex flex-col">
                <div class="flex justify-between items-center border-b border-gray-100/50 pb-5 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Top Offres</h3>
                    <i class="bi bi-trophy-fill text-amber-400 text-xl"></i>
                </div>
                <ul class="space-y-4 flex-grow">
                    @forelse($top_offres as $index => $offre)
                        <li class="p-4 rounded-2xl border {{ $index === 0 ? 'border-amber-200 bg-amber-50/50' : 'border-gray-100/70 hover:bg-gray-50/50' }} transition duration-150 flex justify-between items-center group relative overflow-hidden">
                            @if($index === 0)
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-amber-400 to-orange-500"></div>
                            @endif
                            <div class="pl-2">
                                <p class="text-sm font-bold text-gray-900">{{ $offre->titre }}</p>
                                <p class="text-[11px] text-gray-400 mt-1 font-medium">{{ mb_strtoupper($offre->type) }}</p>
                            </div>
                            <span class="inline-flex flex-col items-center justify-center p-2 rounded-xl {{ $index === 0 ? 'bg-white shadow-sm' : 'bg-gray-50 border border-gray-100' }}">
                                <span class="text-xs font-black {{ $index === 0 ? 'text-amber-600' : 'text-gray-700' }}">{{ $offre->candidatures_count }}</span>
                            </span>
                        </li>
                    @empty
                        <li class="py-12 text-sm text-gray-400 font-medium text-center bg-gray-50/50 rounded-2xl border border-dashed border-gray-200 h-full flex flex-col items-center justify-center">
                            <i class="bi bi-graph-down text-3xl mb-3 text-gray-300"></i>
                            Aucune donnée de performance.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.renderEntrepriseCharts = function() {
        const ctx = document.getElementById('entrepriseChart');
        if(ctx && window.Chart) {
            new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Volume',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: [
                            'rgba(245, 158, 11, 0.7)',  // Warning (Pending)
                            'rgba(14, 165, 233, 0.7)',  // Info (Shortlisted)
                            'rgba(99, 102, 241, 0.7)',  // Primary (Interview)
                            'rgba(16, 185, 129, 0.7)',  // Success (Accepted)
                            'rgba(239, 68, 68, 0.7)'    // Danger (Rejected)
                        ],
                        borderColor: [
                            'rgb(245, 158, 11)',
                            'rgb(14, 165, 233)',
                            'rgb(99, 102, 241)',
                            'rgb(16, 185, 129)',
                            'rgb(239, 68, 68)'
                        ],
                        borderWidth: 2, 
                        borderRadius: 8,
                        barThickness: 40
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false,
                    scales: { 
                        y: { beginAtZero: true, grid: { borderDash: [4, 4], color: '#f3f4f6' }, border: { display: false }, ticks: { stepSize: 1, font: {family: 'Inter'} } },
                        x: { grid: { display: false }, border: { display: false }, ticks: { font: {family: 'Inter', weight: 600} } }
                    },
                    plugins: { legend: { display: false } },
                    animation: { y: { duration: 1500, easing: 'easeOutQuart' } }
                }
            });
        }
    };
</script>
@endpush

@endsection
