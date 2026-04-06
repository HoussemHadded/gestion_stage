@extends('layouts.app')

@section('title', 'Tableau de Bord Entreprise')

@section('content')

{{-- En-tête --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-building me-2 text-warning"></i>Tableau de Bord Entreprise</h2>
        <p class="text-muted mb-0">Bienvenue, <strong>{{ auth()->user()->name }}</strong>
            @if(auth()->user()->company_name)
                — {{ auth()->user()->company_name }}
            @endif
        </p>
    </div>
    <a href="{{ route('entreprise.offres.create') }}" class="btn btn-warning">
        <i class="bi bi-plus-circle me-1"></i>Publier une offre
    </a>
</div>

{{-- Statistiques --}}
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-briefcase-fill fs-3 text-warning"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold">{{ $totalOffres }}</div>
                    <div class="text-muted small">Offres publiées</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-people-fill fs-3 text-primary"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold">{{ $totalCandidatures }}</div>
                    <div class="text-muted small">Candidatures reçues</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-3 bg-success bg-opacity-10 p-3">
                    <i class="bi bi-graph-up-arrow fs-3 text-success"></i>
                </div>
                <div>
                    <div class="fs-2 fw-bold">
                        {{ $totalOffres > 0 ? number_format($totalCandidatures / $totalOffres, 1) : 0 }}
                    </div>
                    <div class="text-muted small">Moy. candidatures / offre</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Dernières offres --}}
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-briefcase me-2 text-warning"></i>Mes dernières offres
        </h5>
        <a href="{{ route('entreprise.offres.index') }}" class="btn btn-sm btn-outline-warning">
            Voir toutes →
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th class="text-center">Candidatures</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($offres as $offre)
                        <tr>
                            <td class="fw-medium">{{ $offre->titre }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill">{{ $offre->candidatures_count }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('entreprise.offres.edit', $offre->id) }}"
                                   class="btn btn-sm btn-outline-secondary me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('entreprise.candidatures.index') }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-people"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Aucune offre publiée.
                                <a href="{{ route('entreprise.offres.create') }}" class="ms-2">Créer votre première offre →</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
