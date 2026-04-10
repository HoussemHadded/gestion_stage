@extends('layouts.app')

@section('title', 'Offres de Stage')

@section('content')

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-3xl font-extrabold text-gray-900 flex items-center">
            <i class="bi bi-briefcase-fill text-indigo-600 mr-3"></i>Offres de Stage
        </h2>
        <p class="mt-1 text-sm text-gray-500">Parcourez les offres disponibles et postulez au stage de vos rêves.</p>
    </div>
</div>

@if($offres->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <i class="bi bi-inbox text-6xl text-gray-300 mb-4 inline-block"></i>
        <h5 class="text-xl font-bold text-gray-700 mb-2">Aucune offre disponible pour le moment</h5>
        <p class="text-gray-500">Revenez plus tard, de nouvelles opportunités seront publiées bientôt.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($offres as $offre)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex items-start mb-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex flex-shrink-0 items-center justify-center text-xl mr-4">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <h6 class="font-bold text-gray-900 leading-tight mb-1">{{ $offre->titre }}</h6>
                            <p class="text-sm font-medium text-gray-500 flex items-center">
                                <i class="bi bi-building mr-1.5"></i>{{ $offre->entreprise->name ?? 'Entreprise inconnue' }}
                            </p>
                        </div>
                    </div>

                    <p class="text-gray-600 text-sm mb-4 flex-grow line-clamp-3">
                        {{ $offre->description }}
                    </p>

                    <div class="flex flex-wrap gap-2 mb-6 mt-auto">
                        @if($offre->lieu)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                <i class="bi bi-geo-alt mr-1"></i>{{ $offre->lieu }}
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="text-xs font-medium text-gray-400 flex items-center">
                            <i class="bi bi-calendar3 mr-1.5"></i>
                            {{ $offre->date_publication ? \Carbon\Carbon::parse($offre->date_publication)->format('d/m/Y') : '—' }}
                        </span>
                        <form action="{{ route('student.offres.postuler', $offre->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg shadow-sm transition">
                                <i class="bi bi-send mr-2"></i>Postuler
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8 flex justify-center">
        {{ $offres->links() }}
    </div>
@endif

@endsection
