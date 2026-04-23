@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('content')

<div x-data="{ loaded: false }" x-init="setTimeout(() => { loaded = true; if(window.renderAdminCharts) window.renderAdminCharts(); }, 800)">

    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center">
                <i class="bi bi-speedometer2 text-indigo-500 mr-3"></i>Dashboard Admin
            </h2>
            <p class="mt-2 text-sm text-gray-500 font-medium">Vue globale des statistiques de la plateforme.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.export.users') }}" class="px-5 py-2.5 glass-card hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-xl transition flex items-center premium-hover">
                <i class="bi bi-person-lines-fill text-indigo-500 mr-2"></i>Export Utilisateurs
            </a>
            <a href="{{ route('admin.export.offres') }}" class="px-5 py-2.5 glass-card hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-xl transition flex items-center premium-hover">
                <i class="bi bi-briefcase-fill text-amber-500 mr-2"></i>Export Offres
            </a>
            <a href="{{ route('admin.export.candidatures') }}" class="px-5 py-2.5 glass-card hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-xl transition flex items-center premium-hover">
                <i class="bi bi-file-earmark-check-fill text-emerald-500 mr-2"></i>Export Candidatures
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="h-96 rounded-2xl sk-loading w-full"></div>
            <div class="h-96 rounded-2xl sk-loading w-full"></div>
        </div>
    </div>

    {{-- Loaded Content --}}
    <div x-show="loaded" style="display: none;" x-transition:enter="transition ease-out duration-700" opacity-0 transform translate-y-4 opacity-100 translate-y-0>
        
        {{-- KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass-card rounded-2xl p-6 flex flex-col premium-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-100 to-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-2xl mb-4 relative z-10 shadow-sm border border-indigo-100/50">
                    <i class="bi bi-people-fill"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest relative z-10">Utilisateurs</p>
                <h3 class="text-4xl font-extrabold text-gray-900 mt-2 relative z-10">{{ $total_users }}</h3>
                <p class="text-xs text-gray-500 mt-3 font-medium relative z-10 flex items-center"><span class="text-indigo-500 mr-1">•</span> {{ $total_students }} étudiants <span class="text-amber-500 mx-1">•</span> {{ $total_entreprises }} entreprises</p>
            </div>

            <div class="glass-card rounded-2xl p-6 flex flex-col premium-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-2xl mb-4 relative z-10 shadow-sm border border-emerald-100/50">
                    <i class="bi bi-briefcase-fill"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest relative z-10">Offres Publiées</p>
                <h3 class="text-4xl font-extrabold text-gray-900 mt-2 relative z-10">{{ $total_offres }}</h3>
                <div class="mt-3 flex items-center text-xs font-medium text-emerald-600 relative z-10"><i class="bi bi-arrow-up-right mr-1"></i> Dynamique</div>
            </div>

            <div class="glass-card rounded-2xl p-6 flex flex-col premium-hover relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-2xl mb-4 relative z-10 shadow-sm border border-blue-100/50">
                    <i class="bi bi-send-fill"></i>
                </div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest relative z-10">Candidatures</p>
                <h3 class="text-4xl font-extrabold text-gray-900 mt-2 relative z-10">{{ $total_candidatures }}</h3>
                <div class="w-full bg-gray-100 rounded-full h-1 mt-4 relative z-10"><div class="bg-blue-500 h-1 rounded-full" style="width: 70%"></div></div>
            </div>

            <div class="bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800 rounded-2xl shadow-xl border border-indigo-700/50 p-6 flex flex-col text-white premium-hover relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-purple-500 rounded-full blur-3xl opacity-20"></div>
                <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center text-2xl mb-4 relative z-10 border border-white/10">
                    <i class="bi bi-award-fill text-purple-300"></i>
                </div>
                <p class="text-xs font-bold text-indigo-300 uppercase tracking-widest relative z-10">Stages Validés</p>
                <h3 class="text-4xl font-extrabold mt-2 relative z-10">{{ $accepted_candidatures }}</h3>
                <p class="text-xs text-indigo-200 mt-3 font-medium relative z-10">Succès réseau</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="glass-card rounded-3xl p-8 relative overflow-hidden">
                <div class="flex justify-between items-center border-b border-gray-100/50 pb-5 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Répartition Candidatures</h3>
                    <div class="p-2 bg-indigo-50 rounded-lg"><i class="bi bi-pie-chart-fill text-indigo-500"></i></div>
                </div>
                <div class="chart-container flex items-center justify-center">
                    <canvas id="candidaturesChart"></canvas>
                </div>
            </div>

            <div class="glass-card rounded-3xl p-8">
                <div class="flex justify-between items-center border-b border-gray-100/50 pb-5 mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Croissance Utilisateurs</h3>
                    <div class="p-2 bg-emerald-50 rounded-lg"><i class="bi bi-activity text-emerald-500"></i></div>
                </div>
                <div class="overflow-hidden rounded-2xl border border-gray-100">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-4 py-4 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Identité</th>
                                <th class="px-4 py-4 text-right text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Rôle</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 bg-transparent">
                            @forelse($recent_users as $ru)
                            <tr class="hover:bg-gray-50/50 transition duration-150">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-tr from-gray-100 to-gray-200 flex items-center justify-center font-bold text-gray-600 text-xs border border-white shadow-sm">
                                            {{ substr($ru->name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-bold text-gray-900">{{ $ru->name }}</p>
                                            <p class="text-[11px] text-gray-500">{{ $ru->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-right">
                                    <span class="{{ $ru->role->badgeClass() }} px-3 py-1.5 rounded-xl text-[10px] font-extrabold shadow-sm border border-black/5">
                                        {{ mb_strtoupper($ru->role->label()) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="px-4 py-8 text-center text-sm text-gray-500 font-medium tracking-wide">La base est vide...</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    window.renderAdminCharts = function() {
        const ctx = document.getElementById('candidaturesChart');
        if(ctx && window.Chart) {
            new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{ 
                        data: {!! json_encode($chartData) !!}, 
                        backgroundColor: ['#f59e0b', '#10b981', '#ef4444'], 
                        borderWidth: 4, 
                        borderColor: '#ffffff',
                        hoverOffset: 8,
                        borderRadius: 4
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { position: 'bottom', labels: { padding: 20, font: { family: 'Inter', weight: '600' } } } 
                    }, 
                    cutout: '75%',
                    animation: { animateScale: true, animateRotate: true }
                }
            });
        }
    };
</script>
@endpush

@endsection
