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
        {{ $candidatures->links() }}
    </div>
@endif
@endsection
