@extends('layouts.app')

@section('title', 'Offres de Stage')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-briefcase-fill me-2"></i>Offres de Stage</h2>
        <p class="text-muted mb-0">Parcourez les offres disponibles et postulez</p>
    </div>
    <a href="{{ route('student.candidatures.create') }}" class="btn btn-primary">
        <i class="bi bi-pencil-square me-1"></i>Postuler
    </a>
</div>

@if($offres->isEmpty())
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
            <h5 class="text-muted">Aucune offre disponible pour le moment</h5>
            <p class="text-muted small">Revenez plus tard, de nouvelles offres seront publiées bientôt.</p>
        </div>
    </div>
@else
    <div class="row g-4">
        @foreach($offres as $offre)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100 border-0" style="border-radius: 12px;">
                    <div class="card-body p-4 d-flex flex-column">
                        <div class="d-flex align-items-start mb-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 flex-shrink-0"
                                 style="width:44px;height:44px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-building text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0">{{ $offre->titre }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-building me-1"></i>{{ $offre->entreprise->name ?? 'Entreprise inconnue' }}
                                </small>
                            </div>
                        </div>

                        <p class="text-muted small mb-3 flex-grow-1" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $offre->description }}
                        </p>

                        <div class="d-flex flex-wrap gap-2 mb-3">
                            @if($offre->lieu)
                                <span class="badge bg-light text-dark border" style="font-weight:500;">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $offre->lieu }}
                                </span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-auto">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ $offre->date_publication ? \Carbon\Carbon::parse($offre->date_publication)->format('d/m/Y') : '—' }}
                            </small>
                            <a href="{{ route('student.candidatures.create', ['offre_id' => $offre->id]) }}"
                               class="btn btn-sm btn-primary"
                               style="border-radius:8px;">
                                <i class="bi bi-send me-1"></i>Postuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $offres->links() }}
    </div>
@endif

@endsection
