@extends('layouts.app')

@section('title', 'Liste des Offres de Stage')

@section('content')

{{-- ======================== EN-TÊTE ======================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-briefcase-fill me-2 text-primary"></i>Offres de Stage</h2>
        <p class="text-muted mb-0">Découvrez les opportunités de stage disponibles</p>
    </div>

    @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.offres.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nouvelle Offre
        </a>
    @elseif(auth()->user()->isEntreprise())
        <a href="{{ route('entreprise.offres.create') }}" class="btn btn-warning">
            <i class="bi bi-plus-circle me-1"></i>Publier une Offre
        </a>
    @elseif(auth()->user()->isStudent())
        <a href="{{ route('student.candidatures.create') }}" class="btn btn-success">
            <i class="bi bi-pencil-square me-1"></i>Postuler
        </a>
    @endif
</div>

{{-- ======================== TABLEAU DES OFFRES ======================== --}}
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
                        @if(auth()->user()->isAdmin() || auth()->user()->isEntreprise())
                            <th class="text-center">Actions</th>
                        @endif
                        @if(auth()->user()->isStudent())
                            <th class="text-center">Action</th>
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

                            {{-- Actions Admin --}}
                            @if(auth()->user()->isAdmin())
                                <td class="text-center">
                                    <a href="{{ route('admin.offres.edit', $offre->id) }}" class="btn btn-sm btn-warning btn-action">
                                        <i class="bi bi-pencil-square"></i> Modifier
                                    </a>
                                    <form action="{{ route('admin.offres.destroy', $offre->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer cette offre ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-action">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            @endif

                            {{-- Actions Entreprise --}}
                            @if(auth()->user()->isEntreprise())
                                <td class="text-center">
                                    <a href="{{ route('entreprise.offres.edit', $offre->id) }}" class="btn btn-sm btn-warning btn-action">
                                        <i class="bi bi-pencil-square"></i> Modifier
                                    </a>
                                    <form action="{{ route('entreprise.offres.destroy', $offre->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Supprimer cette offre ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger btn-action">
                                            <i class="bi bi-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            @endif

                            {{-- Action Étudiant —  Postuler --}}
                            @if(auth()->user()->isStudent())
                                <td class="text-center">
                                    <a href="{{ route('student.candidatures.create') }}?offre_id={{ $offre->id }}"
                                       class="btn btn-sm btn-success">
                                        <i class="bi bi-send me-1"></i>Postuler
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Aucune offre disponible pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ======================== PAGINATION ======================== --}}
@if($offres instanceof \Illuminate\Pagination\LengthAwarePaginator && $offres->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $offres->appends(request()->query())->links() }}
    </div>
@endif

@endsection
