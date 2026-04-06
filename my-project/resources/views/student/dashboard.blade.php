@extends('layouts.app')

@section('title', 'Mon Tableau de Bord')

@section('content')

<div class="mb-4">
    <h2><i class="bi bi-speedometer2 me-2"></i>Mon Tableau de Bord</h2>
    <p class="text-muted mb-0">Bonjour <strong>{{ auth()->user()->name }}</strong>, voici un résumé de votre parcours.</p>
</div>

{{-- ======================== MES CANDIDATURES ======================== --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-file-earmark-text-fill me-2 text-primary"></i>Mes candidatures
        </h5>
        <span class="badge bg-primary rounded-pill">{{ $candidatures->count() }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>Offre</th>
                        <th>Entreprise</th>
                        <th>Date</th>
                        <th class="text-center">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures as $candidature)
                        <tr>
                            <td>{{ $candidature->offre->titre ?? '—' }}</td>
                            <td>{{ $candidature->offre->entreprise->name ?? '—' }}</td>
                            <td>
                                {{ $candidature->date_candidature
                                    ? $candidature->date_candidature->format('d/m/Y')
                                    : '—' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $candidature->statut->badgeClass() }}">
                                    {{ $candidature->statut->label() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">
                                <i class="bi bi-inbox me-2"></i>Aucune candidature soumise.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======================== RÉPONSE ENTREPRISE ======================== --}}
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-building-check me-2 text-info"></i>Réponse entreprise
        </h5>
    </div>
    <div class="card-body">
        @php
            $accepted = $candidatures->filter(fn($c) => $c->statut->value === 'accepte');
            $refused  = $candidatures->filter(fn($c) => $c->statut->value === 'refuse');
            $pending  = $candidatures->filter(fn($c) => $c->statut->value === 'en_attente');
        @endphp

        <div class="row g-3 text-center">
            <div class="col-12 col-sm-4">
                <div class="border rounded p-3 h-100">
                    <div class="fs-2 fw-bold text-success">{{ $accepted->count() }}</div>
                    <div class="text-muted small">
                        <i class="bi bi-check-circle-fill text-success me-1"></i>Acceptée(s)
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="border rounded p-3 h-100">
                    <div class="fs-2 fw-bold text-warning">{{ $pending->count() }}</div>
                    <div class="text-muted small">
                        <i class="bi bi-hourglass-split text-warning me-1"></i>En attente
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="border rounded p-3 h-100">
                    <div class="fs-2 fw-bold text-danger">{{ $refused->count() }}</div>
                    <div class="text-muted small">
                        <i class="bi bi-x-circle-fill text-danger me-1"></i>Refusée(s)
                    </div>
                </div>
            </div>
        </div>

        @if($accepted->isNotEmpty())
            <div class="alert alert-success mt-4 mb-0">
                <i class="bi bi-check-circle me-2"></i>
                Félicitations ! Votre candidature pour l'offre
                <strong>« {{ $accepted->first()->offre->titre ?? '—' }} »</strong>
                a été acceptée.
            </div>
        @elseif($pending->isNotEmpty())
            <div class="alert alert-warning mt-4 mb-0">
                <i class="bi bi-hourglass-split me-2"></i>
                Vous avez {{ $pending->count() }} candidature(s) en attente de réponse.
            </div>
        @elseif($candidatures->isEmpty())
            <div class="alert alert-info mt-4 mb-0">
                <i class="bi bi-info-circle me-2"></i>
                Vous n'avez pas encore soumis de candidature.
                <a href="{{ route('student.candidatures.create') }}" class="alert-link">Postuler maintenant →</a>
            </div>
        @endif
    </div>
</div>

@endsection
