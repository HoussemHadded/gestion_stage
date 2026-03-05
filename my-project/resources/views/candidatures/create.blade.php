@extends('layouts.app')

@section('title', 'Créer une Candidature')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-plus-circle-fill me-2"></i>Nouvelle Candidature</h2>
            <a href="{{ route('offres.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Retour aux offres
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('candidatures.store') }}" method="POST">
                    @csrf

                    {{-- Étudiant --}}
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Étudiant <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id"
                                class="form-select @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner un étudiant --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Offre --}}
                    <div class="mb-3">
                        <label for="offre_id" class="form-label">Offre de stage <span class="text-danger">*</span></label>
                        <select name="offre_id" id="offre_id"
                                class="form-select @error('offre_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner une offre --</option>
                            @foreach($offres as $offre)
                                <option value="{{ $offre->id }}"
                                    {{ old('offre_id') == $offre->id ? 'selected' : '' }}>
                                    {{ $offre->titre }}
                                </option>
                            @endforeach
                        </select>
                        @error('offre_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CV --}}
                    <div class="mb-3">
                        <label for="cv" class="form-label">CV / Lettre de motivation <span class="text-danger">*</span></label>
                        <textarea name="cv" id="cv" rows="5"
                                  class="form-control @error('cv') is-invalid @enderror"
                                  placeholder="Décrivez votre parcours, compétences et motivations..."
                                  required>{{ old('cv') }}</textarea>
                        @error('cv')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Statut --}}
                    <div class="mb-3">
                        <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                        <select name="statut" id="statut"
                                class="form-select @error('statut') is-invalid @enderror" required>
                            <option value="en_attente" {{ old('statut', 'en_attente') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="accepte" {{ old('statut') === 'accepte' ? 'selected' : '' }}>Accepté</option>
                            <option value="refuse" {{ old('statut') === 'refuse' ? 'selected' : '' }}>Refusé</option>
                        </select>
                        @error('statut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date de candidature --}}
                    <div class="mb-3">
                        <label for="date_candidature" class="form-label">Date de candidature <span class="text-danger">*</span></label>
                        <input type="date" name="date_candidature" id="date_candidature"
                               class="form-control @error('date_candidature') is-invalid @enderror"
                               value="{{ old('date_candidature', date('Y-m-d')) }}" required>
                        @error('date_candidature')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send me-1"></i>Soumettre la candidature
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
