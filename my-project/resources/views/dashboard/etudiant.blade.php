@extends('layouts.app')

@section('title', 'Tableau de bord Étudiant')

@section('content')

<div x-data="{ loaded: false }" x-init="setTimeout(() => { loaded = true; if(window.renderStudentCharts) window.renderStudentCharts(); }, 800)">

    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center">
                <i class="bi bi-mortarboard text-blue-600 mr-3"></i>Espace Étudiant
            </h2>
            <p class="mt-2 text-sm text-gray-500 font-medium">Gérez vos candidatures, analysez vos scores et décrochez le stage idéal.</p>
        </div>
        <a href="{{ route('student.offres.index') }}" class="px-6 py-3 bg-gradient-to-tr from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl shadow-[0_8px_20px_rgb(59,130,246,0.3)] transition transform hover:-translate-y-1 premium-hover flex items-center">
            <i class="bi bi-search mr-2"></i>Explorer les offres
        </a>
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
        
        {{-- AI Insights --}}
        <div class="mb-8 p-8 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-900 via-indigo-950 to-gray-900 rounded-3xl shadow-[0_20px_50px_rgb(0,0,0,0.5)] text-white relative overflow-hidden group premium-hover">
            <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500 rounded-full blur-[80px] opacity-20 group-hover:opacity-40 transition-opacity duration-1000"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 relative z-10 border-b border-indigo-800/50 pb-6">
                <div>
                    <h3 class="text-2xl font-extrabold flex items-center tracking-wide">
                        <i class="bi bi-robot mr-3 text-indigo-400 bg-indigo-500/10 p-2 rounded-xl border border-indigo-400/20"></i>Assistant Carrière IA
                    </h3>
                    <p class="text-indigo-300 text-sm mt-2 font-medium">Analyse paramétrique de votre profil face aux exigences du marché.</p>
                </div>
                <a href="{{ route('student.match.index') }}" class="mt-6 md:mt-0 text-sm bg-white/10 hover:bg-white/20 px-6 py-2.5 rounded-xl font-bold transition backdrop-blur-md border border-white/5 flex items-center">
                    <i class="bi bi-magic mr-2 text-indigo-300"></i>Optimiser mon CV
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                <div class="bg-black/20 backdrop-blur-sm rounded-2xl p-6 border border-white/5 hover:border-indigo-400/30 transition">
                    <p class="text-indigo-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-2">Radar</p>
                    <p class="text-4xl font-black text-white">{{ $total_matches }}</p>
                    <p class="text-xs text-indigo-300 mt-2 font-medium">Offres analysées</p>
                </div>
                <div class="bg-black/20 backdrop-blur-sm rounded-2xl p-6 border border-white/5 hover:border-indigo-400/30 transition">
                    <p class="text-blue-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-2">Score Moyen</p>
                    <div class="flex items-end">
                        <p class="text-4xl font-black">{{ round($average_score) }}</p>
                        <span class="text-indigo-400 ml-1 mb-1 font-bold text-sm">/ 100</span>
                    </div>
                    <div class="w-full bg-indigo-950/50 rounded-full h-1.5 mt-4 shadow-inner border border-white/5">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-400 h-1.5 rounded-full relative" style="width: {{ $average_score }}%">
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-2 h-2 bg-white rounded-full shadow-[0_0_10px_#fff]"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-black/20 backdrop-blur-sm rounded-2xl p-6 border border-white/5 hover:border-yellow-400/30 transition">
                    <p class="text-yellow-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-2">Top Match</p>
                    <div class="flex items-end">
                        <p class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-500">{{ round($best_score) }}</p>
                        <span class="text-indigo-400 ml-1 mb-1 font-bold text-sm">/ 100</span>
                    </div>
                    <div class="w-full bg-indigo-950/50 rounded-full h-1.5 mt-4 shadow-inner border border-white/5">
                        <div class="bg-gradient-to-r from-amber-500 to-yellow-400 h-1.5 rounded-full relative" style="width: {{ $best_score }}%">
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 w-2 h-2 bg-white rounded-full shadow-[0_0_10px_#fff]"></div>
                        </div>
                    </div>
                </div>
                <div class="bg-black/20 backdrop-blur-sm rounded-2xl p-6 border border-white/5 hover:border-purple-400/30 transition">
                    <p class="text-purple-400 text-[10px] uppercase tracking-[0.2em] font-bold mb-2">Taux Optimisation</p>
                    <div class="flex items-end">
                        <p class="text-4xl font-black">{{ round($optimization_rate) }}</p>
                        <span class="text-indigo-400 ml-1 mb-1 font-bold text-sm">%</span>
                    </div>
                    <p class="text-[11px] text-indigo-300 mt-2 font-medium bg-purple-900/40 py-1 px-2 rounded-md inline-block">{{ $optimized_count }} candidatures ciblées</p>
                </div>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass-card rounded-2xl p-6 flex flex-col premium-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-gray-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest relative z-10 flex items-center"><i class="bi bi-send-fill mr-1.5 text-gray-500"></i>Candidatures</p>
                <h3 class="text-4xl font-extrabold text-gray-900 mt-3 relative z-10">{{ $total_candidatures }}</h3>
                <p class="text-xs text-gray-500 mt-1font-medium relative z-10 border-t border-gray-100 pt-3 mt-4">Applications globales</p>
            </div>
            
            <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-2xl shadow-lg border border-emerald-400 p-6 flex flex-col text-white premium-hover relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/20 rounded-full"></div>
                <p class="text-[10px] font-extrabold text-emerald-100 uppercase tracking-widest relative z-10 flex items-center"><i class="bi bi-check-circle-fill mr-1.5"></i>Acceptées</p>
                <h3 class="text-4xl font-extrabold mt-3 relative z-10">{{ $accepted }}</h3>
                <div class="w-full bg-emerald-700/50 rounded-full h-1 mt-4 relative z-10"><div class="bg-white h-1 rounded-full" style="width: 100%"></div></div>
            </div>

            <div class="bg-gradient-to-br from-amber-400 to-orange-400 rounded-2xl shadow-lg border border-amber-300 p-6 flex flex-col text-white premium-hover relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/20 rounded-full"></div>
                <p class="text-[10px] font-extrabold text-amber-100 uppercase tracking-widest relative z-10 flex items-center"><i class="bi bi-hourglass-split mr-1.5"></i>En Attente</p>
                <h3 class="text-4xl font-extrabold mt-3 relative z-10">{{ $pending }}</h3>
                <div class="w-full bg-amber-600/50 rounded-full h-1 mt-4 relative z-10"><div class="bg-white h-1 rounded-full" style="width: 50%"></div></div>
            </div>

            <div class="glass-card border-red-200/50 rounded-2xl p-6 flex flex-col premium-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-red-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <p class="text-[10px] font-extrabold text-red-400 uppercase tracking-widest relative z-10 flex items-center"><i class="bi bi-x-circle-fill mr-1.5 text-red-500"></i>Refus</p>
                <h3 class="text-4xl font-extrabold text-gray-900 mt-3 relative z-10">{{ $rejected }}</h3>
                <p class="text-xs text-gray-500 mt-1font-medium relative z-10 border-t border-gray-100 pt-3 mt-4">Pistes fermées</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 glass-card rounded-3xl p-8">
                <div class="flex justify-between items-center border-b border-gray-100/50 pb-5 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Pipeline Récents</h3>
                    <a href="{{ route('student.candidatures.index') }}" class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest hover:text-indigo-800 transition">Tout voir &rarr;</a>
                </div>
                <div class="overflow-hidden rounded-2xl border border-gray-100 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-5 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Position</th>
                                <th class="px-5 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Mois</th>
                                <th class="px-5 py-4 text-right text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Santé</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-white">
                            @forelse($candidatures as $c)
                                <tr class="hover:bg-blue-50/20 transition duration-200">
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-bold text-gray-900">{{ $c->offre->titre }}</p>
                                        <p class="text-[11px] text-gray-500 font-medium mt-0.5"><i class="bi bi-building mr-1"></i>{{ $c->offre->entreprise->name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center text-xs font-bold text-gray-500 bg-gray-50 w-max px-3 py-1.5 rounded-lg border border-gray-100">
                                            <i class="bi bi-calendar-event mr-2 text-indigo-400"></i>
                                            {{ $c->date_candidature ? \Carbon\Carbon::parse($c->date_candidature)->format('d M') : 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        @php
                                            $color = match($c->statut->value) {
                                                'acceptée'   => 'bg-emerald-100 text-emerald-800 border-emerald-200 shadow-[0_0_10px_rgba(16,185,129,0.2)]',
                                                'en attente' => 'bg-amber-100 text-amber-800 border-amber-200 shadow-[0_0_10px_rgba(245,158,11,0.2)]',
                                                default      => 'bg-red-100 text-red-800 border-red-200 shadow-[0_0_10px_rgba(239,68,68,0.2)]',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-xl border text-[11px] font-extrabold tracking-wide {{ $color }}">
                                            {{ mb_strtoupper($c->statut->label()) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-5 py-10 text-center text-sm text-gray-400 font-medium bg-gray-50/50">Votre historique est vide. <a href="{{ route('student.offres.index') }}" class="text-indigo-600 hover:underline">Découvrez les offres.</a></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        
            <div class="glass-card rounded-3xl p-8 flex flex-col">
                <div class="flex justify-between items-center border-b border-gray-100/50 pb-5 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Équilibre</h3>
                    <div class="p-2 bg-blue-50 rounded-lg text-blue-500"><i class="bi bi-pie-chart-fill"></i></div>
                </div>
                <div class="flex-grow flex items-center justify-center">
                    @if($total_candidatures > 0)
                        <div class="chart-container"><canvas id="studentChart"></canvas></div>
                    @else
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mx-auto mb-3">
                                <i class="bi bi-bar-chart-steps text-2xl"></i>
                            </div>
                            <p class="text-sm font-medium text-gray-400">Pas assez de données statistiques.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if($total_candidatures > 0)
<script>
    window.renderStudentCharts = function() {
        const ctx = document.getElementById('studentChart');
        if(ctx && window.Chart) {
            new Chart(ctx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{ 
                        data: {!! json_encode($chartData) !!}, 
                        backgroundColor: ['#f59e0b', '#10b981', '#ef4444'], 
                        borderWidth: 4,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { legend: { position: 'bottom', labels: { padding: 20, font: {family: 'Inter', weight: 600} } } },
                    animation: { animateScale: true }
                }
            });
        }
    };
</script>
@endif
@endpush

@endsection
