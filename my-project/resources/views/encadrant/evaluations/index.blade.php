@extends('layouts.app')

@section('title', 'Mes Évaluations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-clipboard-check-fill me-2"></i>Mes Évaluations</h2>
    <a href="{{ route('encadrant.evaluations.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Nouvelle Évaluation
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Étudiant</th>
                        <th>Stage (Offre)</th>
                        <th class="text-center">Note /20</th>
                        <th>Mention</th>
                        <th>Date</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evaluations as $evaluation)
                        <tr>
                            <td>{{ $evaluation->id }}</td>
                            <td>
                                <i class="bi bi-person me-1 text-muted"></i>
                                {{ $evaluation->student->name ?? '—' }}
                            </td>
                            <td>{{ $evaluation->offre->titre ?? '—' }}</td>
                            <td class="text-center fw-bold">{{ $evaluation->note }}</td>
                            <td>
                                <span class="{{ $evaluation->gradeBand()->badgeClass() }}">
                                    {{ $evaluation->gradeBand()->label() }}
                                </span>
                            </td>
                            <td>
                                {{ $evaluation->date_evaluation
                                    ? $evaluation->date_evaluation->format('d/m/Y')
                                    : '—' }}
                            </td>
                            <td class="text-center">
                                {{-- Edit --}}
                                <a href="{{ route('encadrant.evaluations.edit', $evaluation->id) }}"
                                   class="btn btn-sm btn-warning btn-action"
                                   title="Modifier">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- Delete (admin only according to policy) --}}
                                @can('delete', $evaluation)
                                <form action="{{ route('encadrant.evaluations.destroy', $evaluation->id) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer cette évaluation ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-action" title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Aucune évaluation trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
