@extends('layouts.app')

@section('title', 'Mes Candidatures')

@section('content')

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <i class="bi bi-file-earmark-check-fill text-indigo-600 mr-3"></i>Mes Candidatures
        </h2>
        <p class="mt-1 text-sm text-gray-500">Suivez l'état de toutes vos demandes de stage.</p>
    </div>
    <a href="{{ route('student.offres.index') }}" class="px-4 py-2 border border-indigo-200 text-indigo-700 hover:bg-indigo-50 bg-white text-sm font-bold rounded-lg shadow-sm transition">
        <i class="bi bi-search mr-2"></i>Chercher d'autres offres
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Offre</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Entreprise</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Date d'envoi</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($candidatures as $candidature)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-900">{{ $candidature->offre->titre ?? '—' }}</p>
                            @if($candidature->offre && $candidature->offre->lieu)
                                <p class="text-xs text-gray-500 flex items-center mt-1"><i class="bi bi-geo-alt mr-1"></i>{{ $candidature->offre->lieu }}</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="bi bi-building text-xs"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $candidature->offre->entreprise->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                            {{ $candidature->date_candidature ? $candidature->date_candidature->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($candidature->statut->value === 'en attente')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                    {{ $candidature->statut->label() }}
                                </span>
                            @elseif($candidature->statut->value === 'acceptée')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                    {{ $candidature->statut->label() }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                    {{ $candidature->statut->label() }}
                                </span>
                            @endif

                            <div class="mt-2 text-right">
                                <a href="{{ route('student.export.candidature', $candidature->id) }}" class="inline-flex items-center text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition" title="Télécharger l'attestation PDF" data-no-loader>
                                    <i class="bi bi-file-earmark-pdf-fill mr-1 text-red-500"></i>Télécharger PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <i class="bi bi-inbox text-5xl text-gray-300 block mb-3"></i>
                            <p class="text-gray-500 font-medium">Vous n'avez envoyé aucune candidature pour le moment.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($candidatures->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $candidatures->links() }}
    </div>
@endif

@endsection
