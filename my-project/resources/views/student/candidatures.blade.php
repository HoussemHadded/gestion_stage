@extends('layouts.app')

@section('title', 'Mes Candidatures')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-file-earmark-check-fill me-2 text-primary"></i>Mes Candidatures</h2>
        <p class="text-muted mb-0">Suivez l'état de toutes vos demandes de stage.</p>
    </div>
    <a href="{{ route('student.offres.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-search me-1"></i>Chercher d'autres offres
    </a>
</div>

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: rgba(0,0,0,0.02);">
                    <tr>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">Offre</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">Entreprise</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">Date d'envoi</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures as $candidature)
                        <tr>
                            <td class="px-4 py-3 align-middle">
                                <strong>{{ $candidature->offre->titre ?? '—' }}</strong>
                                @if($candidature->offre && $candidature->offre->lieu)
                                    <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $candidature->offre->lieu }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary bg-opacity-10 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="bi bi-building text-primary small"></i>
                                    </div>
                                    {{ $candidature->offre->entreprise->name ?? '—' }}
                                </div>
                            </td>
                            <td class="px-4 py-3 align-middle text-muted">
                                {{ $candidature->date_candidature ? $candidature->date_candidature->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-4 py-3 align-middle">
                                <span class="badge bg-{{ $candidature->statut->badgeClass() }} px-3 py-2 rounded-pill shadow-sm">
                                    {{ $candidature->statut->label() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                Vous n'avez envoyé aucune candidature pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($candidatures->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $candidatures->links() }}
    </div>
@endif

@endsection
