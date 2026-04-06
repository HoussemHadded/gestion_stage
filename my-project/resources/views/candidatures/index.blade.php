@extends('layouts.app')

@section('title', 'Candidatures reçues')

@section('content')

{{-- ======================== EN-TÊTE ======================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-file-earmark-text-fill me-2 text-primary"></i>Candidatures reçues</h2>
        <p class="text-muted mb-0">Gérez les candidatures pour vos offres de stage</p>
    </div>
    <a href="{{ route('entreprise.dashboard') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Retour au dashboard
    </a>
</div>

{{-- ======================== FILTRE PAR STATUT ======================== --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('entreprise.candidatures.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="statut" class="form-label fw-semibold">
                    <i class="bi bi-funnel me-1"></i>Filtrer par statut
                </label>
                <select name="statut" id="statut" class="form-select">
                    <option value="">-- Tous les statuts --</option>
                    <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="accepte"    {{ request('statut') === 'accepte'    ? 'selected' : '' }}>Accepté</option>
                    <option value="refuse"     {{ request('statut') === 'refuse'     ? 'selected' : '' }}>Refusé</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Filtrer
                </button>
                @if(request('statut'))
                    <a href="{{ route('entreprise.candidatures.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-x-circle me-1"></i>Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- ======================== TABLEAU ======================== --}}
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Étudiant</th>
                        <th>Offre</th>
                        <th>CV (extrait)</th>
                        <th>Date</th>
                        <th class="text-center">Statut actuel</th>
                        <th class="text-center">Changer le statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures as $candidature)
                        <tr>
                            <td>{{ $candidature->id }}</td>
                            <td>
                                <strong>{{ $candidature->student->name ?? '—' }}</strong>
                                <div class="text-muted small">{{ $candidature->student->email ?? '' }}</div>
                            </td>
                            <td>{{ $candidature->offre->titre ?? '—' }}</td>
                            <td>
                                <span class="text-muted small">{{ Str::limit($candidature->cv, 60) }}</span>
                            </td>
                            <td>
                                {{ $candidature->date_candidature
                                    ? $candidature->date_candidature->format('d/m/Y')
                                    : '—' }}
                            </td>
                            <td class="text-center">
                                {{-- Utiliser la méthode Enum pour le badge --}}
                                <span class="badge bg-{{ $candidature->statut->badgeClass() }}">
                                    {{ $candidature->statut->label() }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{-- Formulaire PATCH de changement de statut --}}
                                <form action="{{ route('entreprise.candidatures.updateStatut', $candidature->id) }}"
                                      method="POST" class="d-inline-flex gap-1 align-items-center">
                                    @csrf
                                    @method('PATCH')
                                    <select name="statut" class="form-select form-select-sm" style="width:130px;">
                                        <option value="en_attente" {{ $candidature->statut->value === 'en_attente' ? 'selected' : '' }}>En attente</option>
                                        <option value="accepte"    {{ $candidature->statut->value === 'accepte'    ? 'selected' : '' }}>Accepter</option>
                                        <option value="refuse"     {{ $candidature->statut->value === 'refuse'     ? 'selected' : '' }}>Refuser</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="bi bi-check2"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Aucune candidature reçue pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======================== PAGINATION ======================== --}}
@if($candidatures->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $candidatures->appends(request()->query())->links() }}
    </div>
@endif

@endsection
