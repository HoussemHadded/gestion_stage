@extends('layouts.app')

@section('title', 'Liste des Offres')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-briefcase-fill me-2"></i>Liste des Offres de Stage</h2>

    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'entreprise')
        <a href="{{ route('offres.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nouvelle Offre
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
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Entreprise</th>
                        <th>Date de publication</th>
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'entreprise')
                            <th class="text-center">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($offres as $offre)
                        <tr>
                            <td>{{ $offre->id }}</td>
                            <td><strong>{{ $offre->titre }}</strong></td>
                            <td>{{ Str::limit($offre->description, 80) }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $offre->entreprise->name ?? '—' }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($offre->date_publication)->format('d/m/Y') }}</td>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'entreprise')
                                <td class="text-center">
                                    <a href="{{ route('offres.edit', $offre->id) }}" class="btn btn-sm btn-warning btn-action">
                                        <i class="bi bi-pencil-square"></i> Modifier
                                    </a>
                                    <form action="{{ route('offres.destroy', $offre->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer cette offre ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-action">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Aucune offre disponible.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
@if($offres->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $offres->appends(request()->query())->links() }}
    </div>
@endif
@endsection
