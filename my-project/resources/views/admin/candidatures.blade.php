@extends('layouts.app')

@section('title', 'Toutes les Candidatures')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-file-earmark-text-fill me-2 text-danger"></i>Toutes les Candidatures</h2>
        <p class="text-muted mb-0">Vue globale admin : gérez toutes les candidatures de la plateforme.</p>
    </div>
</div>

<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background: rgba(0,0,0,0.02);">
                    <tr>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">ID</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">Étudiant</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">Offre</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">Entreprise</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">Date</th>
                        <th class="border-0 px-4 py-3 text-muted" style="font-weight: 500;">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures as $candidature)
                        <tr>
                            <td class="px-4 py-3 align-middle text-muted">{{ $candidature->id }}</td>
                            <td class="px-4 py-3 align-middle">
                                <strong>{{ $candidature->student->name ?? '—' }}</strong>
                                <div class="text-muted small">{{ $candidature->student->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 align-middle">
                                <strong>{{ $candidature->offre->titre ?? '—' }}</strong>
                            </td>
                            <td class="px-4 py-3 align-middle">
                                {{ $candidature->offre->entreprise->name ?? '—' }}
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
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                Aucune candidature pour le moment.
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
