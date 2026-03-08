@extends('layouts.app')

@section('title', 'Modifier l\'Évaluation')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-pencil-square me-2"></i>Modifier l'Évaluation</h2>
            <a href="{{ route('encadrant.evaluations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('encadrant.evaluations.update', $evaluation->id) }}"
                      method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Étudiant --}}
                    <div class="mb-3">
                        <label for="student_id" class="form-label fw-semibold">
                            Étudiant <span class="text-danger">*</span>
                        </label>
                        <select name="student_id" id="student_id"
                                class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner un étudiant --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ old('student_id', $evaluation->student_id) == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Offre de stage --}}
                    <div class="mb-3">
                        <label for="offre_id" class="form-label fw-semibold">
                            Offre de stage <span class="text-danger">*</span>
                        </label>
                        <select name="offre_id" id="offre_id"
                                class="form-select @error('offre_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner une offre --</option>
                            @foreach($offres as $offre)
                                <option value="{{ $offre->id }}"
                                    {{ old('offre_id', $evaluation->offre_id) == $offre->id ? 'selected' : '' }}>
                                    {{ $offre->titre }}
                                </option>
                            @endforeach
                        </select>
                        @error('offre_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Note --}}
                    <div class="mb-3">
                        <label for="note" class="form-label fw-semibold">
                            Note <span class="text-muted">(0 – 20)</span> <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="note" id="note" min="0" max="20"
                               class="form-control @error('note') is-invalid @enderror"
                               value="{{ old('note', $evaluation->note) }}" required>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Commentaire --}}
                    <div class="mb-3">
                        <label for="commentaire" class="form-label fw-semibold">
                            Commentaire <span class="text-muted">(20 – 2000 caractères)</span>
                            <span class="text-danger">*</span>
                        </label>
                        <textarea name="commentaire" id="commentaire" rows="5"
                                  class="form-control @error('commentaire') is-invalid @enderror"
                                  required>{{ old('commentaire', $evaluation->commentaire) }}</textarea>
                        @error('commentaire')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date d'évaluation --}}
                    <div class="mb-3">
                        <label for="date_evaluation" class="form-label fw-semibold">
                            Date d'évaluation <span class="text-danger">*</span>
                        </label>
                        <input type="date" name="date_evaluation" id="date_evaluation"
                               class="form-control @error('date_evaluation') is-invalid @enderror"
                               value="{{ old('date_evaluation', $evaluation->date_evaluation?->format('Y-m-d')) }}"
                               max="{{ now()->format('Y-m-d') }}" required>
                        @error('date_evaluation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save me-1"></i>Mettre à jour l'évaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
