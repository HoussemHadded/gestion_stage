@extends('layouts.app')

@section('title', 'Liste des Candidatures')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-file-earmark-text-fill me-2"></i>Liste des Candidatures</h2>

    @if(auth()->user()->role === 'student')
        <a href="{{ route('candidatures.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nouvelle Candidature
        </a>
    @endif
</div>

{{-- Filtre par statut --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('candidatures.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="statut" class="form-label fw-semibold">
                    <i class="bi bi-funnel me-1"></i>Filtrer par statut
                </label>
                <select name="statut" id="statut" class="form-select">
                    <option value="">-- Tous les statuts --</option>
                    <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="accepte" {{ request('statut') === 'accepte' ? 'selected' : '' }}>Accepté</option>
                    <option value="refuse" {{ request('statut') === 'refuse' ? 'selected' : '' }}>Refusé</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Filtrer
                </button>
                @if(request('statut'))
                    <a href="{{ route('candidatures.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="bi bi-x-circle me-1"></i>Réinitialiser
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Étudiant</th>
                        <th>Offre</th>
                        <th>Entreprise</th>
                        <th>CV</th>
                        <th>Statut</th>
                        <th>Date</th>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'entreprise')
                            <th class="text-center">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($candidatures as $candidature)
                        <tr>
                            <td>{{ $candidature->id }}</td>
                            <td>{{ $candidature->student->name ?? '—' }}</td>
                            <td>{{ $candidature->offre->titre ?? '—' }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $candidature->offre->entreprise->name ?? '—' }}
                                </span>
                            </td>
                            <td>{{ Str::limit($candidature->cv, 40) }}</td>
                            <td>
                                @if($candidature->statut === 'accepte')
                                    <span class="badge bg-success">Accepté</span>
                                @elseif($candidature->statut === 'refuse')
                                    <span class="badge bg-danger">Refusé</span>
                                @else
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($candidature->date_candidature)->format('d/m/Y') }}</td>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'entreprise')
                                <td class="text-center">
                                    <a href="{{ route('candidatures.edit', $candidature->id) }}" class="btn btn-sm btn-warning btn-action">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('candidatures.destroy', $candidature->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer cette candidature ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-action">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Aucune candidature trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
@if($candidatures->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $candidatures->appends(request()->query())->links() }}
    </div>
@endif
@endsection
