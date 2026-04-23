@extends('layouts.app')

@section('title', 'Candidatures reçues')

@section('content')

{{-- ======================== EN-TÊTE ======================== --}}
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600 flex items-center">
            <i class="bi bi-file-earmark-text-fill text-amber-500 mr-3"></i>Pipeline Candidats
        </h2>
        <p class="mt-2 text-sm text-gray-500 font-medium">Évaluez et triez les talents grâce au Match Score IA.</p>
    </div>
    <a href="{{ route('entreprise.dashboard') }}" class="px-5 py-2.5 glass-card text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-xl shadow-sm transition flex items-center premium-hover">
        <i class="bi bi-grid-1x2-fill text-indigo-500 mr-2"></i>Dashboard
    </a>
</div>

{{-- ======================== FILTRES & TRI ======================== --}}
<div class="glass-card rounded-2xl shadow-sm border border-gray-100 mb-8 p-6">
    <form method="GET" action="{{ route('entreprise.candidatures.index') }}" class="flex flex-col md:flex-row items-end gap-6">
        
        <div class="flex-grow w-full md:w-auto">
            <label for="statut" class="block text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">
                <i class="bi bi-funnel mr-1"></i>Filtrer par statut
            </label>
            <select name="statut" id="statut" class="w-full bg-white border border-gray-200 text-gray-700 font-medium rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 block p-3 shadow-sm transition">
                <option value="">Tous les statuts</option>
                <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="accepte"    {{ request('statut') === 'accepte'    ? 'selected' : '' }}>Accepté</option>
                <option value="refuse"     {{ request('statut') === 'refuse'     ? 'selected' : '' }}>Refusé</option>
            </select>
        </div>

        <div class="flex-grow w-full md:w-auto">
            <label for="sort" class="block text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">
                <i class="bi bi-sort-down mr-1"></i>Trier par
            </label>
            <select name="sort" id="sort" class="w-full bg-indigo-50/50 border border-indigo-100 text-indigo-900 font-bold rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 shadow-sm transition">
                <option value="score" {{ request('sort') === 'score' || !request('sort') ? 'selected' : '' }}>Match Score IA (Recommandé)</option>
                <option value="date"  {{ request('sort') === 'date' ? 'selected' : '' }}>Date de candidature (Plus récent)</option>
                <option value="level" {{ request('sort') === 'level' ? 'selected' : '' }}>Niveau global du profil (CV Score)</option>
            </select>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
            <button type="submit" class="w-full md:w-auto px-6 py-3 bg-gradient-to-tr from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-extrabold rounded-xl shadow-md transition premium-hover flex justify-center items-center">
                Appliquer
            </button>
            @if(request()->hasAny(['statut', 'sort']))
                <a href="{{ route('entreprise.candidatures.index') }}" class="w-full md:w-auto px-4 py-3 bg-white border border-gray-200 text-gray-500 hover:text-gray-700 hover:bg-gray-50 font-bold rounded-xl shadow-sm transition flex justify-center items-center" title="Réinitialiser">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- ======================== TABLEAU ======================== --}}
<div class="glass-card rounded-3xl overflow-hidden border border-gray-100/80 shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-5 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Candidat</th>
                    <th class="px-6 py-5 text-center text-[10px] font-extrabold text-indigo-400 uppercase tracking-wider"><i class="bi bi-robot mr-1"></i>Score IA</th>
                    <th class="px-6 py-5 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Offre ciblée</th>
                    <th class="px-6 py-5 text-left text-[10px] font-extrabold text-gray-400 uppercase tracking-wider hidden md:table-cell">Date</th>
                    <th class="px-6 py-5 text-center text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-5 text-right text-[10px] font-extrabold text-gray-400 uppercase tracking-wider">Décision</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($candidatures as $candidature)
                    <tr class="hover:bg-amber-50/30 transition duration-150">
                        
                        {{-- Identity --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-tr from-gray-100 to-gray-200 flex items-center justify-center font-bold text-gray-600 text-sm border border-gray-50 shadow-sm">
                                    {{ mb_substr($candidature->student->name ?? '?', 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $candidature->student->name ?? 'Anonyme' }}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">{{ $candidature->student->email ?? '' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- AI Match Score --}}
                        <td class="px-6 py-4 text-center">
                            @if(isset($candidature->match_score))
                                @php
                                    $score = $candidature->match_score;
                                    // Colored badge based on score strength
                                    $badgeColor = $score >= 80 ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 
                                                  ($score >= 50 ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-red-100 text-red-700 border-red-200');
                                @endphp
                                <span class="inline-flex items-center justify-center px-3 py-1.5 min-w-[3rem] rounded-xl text-sm font-black border shadow-sm {{ $badgeColor }}">
                                    {{ round($score) }}%
                                </span>
                            @else
                                <span class="text-xs text-gray-400 font-medium bg-gray-100 px-2 py-1 rounded-lg">Non évalué</span>
                            @endif
                        </td>

                        {{-- Offre --}}
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-800">{{ $candidature->offre->titre ?? '—' }}</p>
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-4 text-xs font-bold text-gray-500 hidden md:table-cell">
                            <div class="flex items-center bg-gray-50 w-max px-3 py-1.5 rounded-lg border border-gray-100">
                                <i class="bi bi-calendar-event mr-2 text-indigo-400"></i>
                                {{ $candidature->date_candidature ? $candidature->date_candidature->format('d M y') : '—' }}
                            </div>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 text-center">
                            @if($candidature->statut === \App\Enums\StatutCandidature::EnAttente)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl border border-amber-200 text-[11px] font-extrabold bg-amber-100 text-amber-800 tracking-wide uppercase">
                                    {{ $candidature->statut->label() }}
                                </span>
                            @elseif($candidature->statut === \App\Enums\StatutCandidature::Acceptee)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl border border-emerald-200 text-[11px] font-extrabold bg-emerald-100 text-emerald-800 tracking-wide uppercase">
                                    {{ $candidature->statut->label() }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-xl border border-red-200 text-[11px] font-extrabold bg-red-100 text-red-800 tracking-wide uppercase">
                                    {{ $candidature->statut->label() }}
                                </span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            @if($candidature->statut === \App\Enums\StatutCandidature::EnAttente)
                                <div class="flex justify-end items-center space-x-2">
                                    <form action="{{ route('entreprise.candidatures.accept', $candidature->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-500 hover:text-white rounded-xl shadow-sm transition transform premium-hover" title="Accepter">
                                            <i class="bi bi-check-lg text-lg"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('entreprise.candidatures.reject', $candidature->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center justify-center w-8 h-8 bg-white border border-gray-200 hover:border-red-200 text-gray-400 hover:bg-red-50 hover:text-red-600 rounded-xl shadow-sm transition transform premium-hover" title="Refuser">
                                            <i class="bi bi-x-lg text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-400 text-[10px] uppercase font-bold tracking-widest bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">Clôturé</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center bg-gray-50/50">
                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-sm">
                                <i class="bi bi-inbox text-3xl text-gray-300"></i>
                            </div>
                            <h5 class="text-lg font-bold text-gray-900 mb-1">Pipeline vide</h5>
                            <p class="text-gray-500 text-sm max-w-sm mx-auto">Aucune candidature ne correspond à vos filtres de recherche.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ======================== PAGINATION ======================== --}}
@if($candidatures->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $candidatures->appends(request()->query())->links() }}
    </div>
@endif

@endsection
