@extends('layouts.app')

@section('title', 'Candidatures reçues')

@section('content')

{{-- ======================== EN-TÊTE ======================== --}}
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <i class="bi bi-file-earmark-text-fill text-indigo-600 mr-3"></i>Candidatures reçues
        </h2>
        <p class="mt-1 text-sm text-gray-500">Gérez les candidatures pour vos offres de stage.</p>
    </div>
    <a href="{{ route('entreprise.dashboard') }}" class="px-4 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 bg-white text-sm font-bold rounded-lg shadow-sm transition flex items-center">
        <i class="bi bi-arrow-left mr-2"></i>Retour au dashboard
    </a>
</div>

{{-- ======================== FILTRE PAR STATUT ======================== --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8 p-6">
    <form method="GET" action="{{ route('entreprise.candidatures.index') }}" class="flex flex-col sm:flex-row items-end gap-4">
        <div class="flex-grow w-full sm:w-auto sm:max-w-xs">
            <label for="statut" class="block text-sm font-bold text-gray-700 mb-2">
                <i class="bi bi-funnel mr-1"></i>Filtrer par statut
            </label>
            <select name="statut" id="statut" class="w-full bg-gray-50 border border-gray-200 text-gray-700 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5">
                <option value="">-- Tous les statuts --</option>
                <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="accepte"    {{ request('statut') === 'accepte'    ? 'selected' : '' }}>Accepté</option>
                <option value="refuse"     {{ request('statut') === 'refuse'     ? 'selected' : '' }}>Refusé</option>
            </select>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-sm transition flex justify-center items-center">
                <i class="bi bi-search mr-2"></i>Filtrer
            </button>
            @if(request('statut'))
                <a href="{{ route('entreprise.candidatures.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold rounded-lg shadow-sm transition flex justify-center items-center">
                    <i class="bi bi-x-circle mr-2"></i>Réinitialiser
                </a>
            @endif
        </div>
    </form>
</div>

{{-- ======================== TABLEAU ======================== --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-16">#</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Étudiant</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Offre</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider hidden md:table-cell">CV (extrait)</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Statut actuel</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($candidatures as $candidature)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium">{{ $candidature->id }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-900">{{ $candidature->student->name ?? '—' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $candidature->student->email ?? '' }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                            {{ $candidature->offre->titre ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500 hidden md:table-cell">
                            {{ Str::limit($candidature->cv, 60) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                            {{ $candidature->date_candidature ? $candidature->date_candidature->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($candidature->statut === \App\Enums\StatutCandidature::EnAttente)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                    {{ $candidature->statut->label() }}
                                </span>
                            @elseif($candidature->statut === \App\Enums\StatutCandidature::Acceptee)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    {{ $candidature->statut->label() }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    {{ $candidature->statut->label() }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @if($candidature->statut === \App\Enums\StatutCandidature::EnAttente)
                                <div class="flex justify-center items-center space-x-2">
                                    <form action="{{ route('entreprise.candidatures.accept', $candidature->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg shadow-sm transition" title="Accepter la candidature">
                                            <i class="bi bi-check-circle mr-1.5"></i>Accepter
                                        </button>
                                    </form>

                                    <form action="{{ route('entreprise.candidatures.reject', $candidature->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-red-200 text-red-600 hover:bg-red-50 text-xs font-bold rounded-lg shadow-sm transition" title="Refuser">
                                            <i class="bi bi-x-circle mr-1.5"></i>Refuser
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-gray-400 text-xs font-medium bg-gray-100 px-3 py-1.5 rounded-lg">Traitée</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="bi bi-inbox text-5xl text-gray-300 block mb-3"></i>
                            <p class="text-gray-500 font-medium">Aucune candidature reçue pour le moment.</p>
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
