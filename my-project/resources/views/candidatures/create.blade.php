@extends('layouts.app')

@section('title', 'Postuler à une Offre')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2><i class="bi bi-send-fill me-2 text-success"></i>Postuler à une Offre</h2>
                <p class="text-muted mb-0">Soumettez votre candidature pour un stage</p>
            </div>
            <a href="{{ route('student.offres.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Retour aux offres
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('student.candidatures.store') }}" method="POST">
                    @csrf

                    {{-- Offre --}}
                    <div class="mb-4">
                        <label for="offre_id" class="form-label fw-semibold">
                            Offre de stage <span class="text-danger">*</span>
                        </label>
                        <select name="offre_id" id="offre_id"
                                class="form-select @error('offre_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner une offre --</option>
                            @foreach($offres as $offre)
                                {{-- Pré-sélectionner si passé via ?offre_id= --}}
                                <option value="{{ $offre->id }}"
                                    {{ (old('offre_id', request('offre_id'))) == $offre->id ? 'selected' : '' }}>
                                    {{ $offre->titre }}
                                    @if($offre->entreprise)
                                        — {{ $offre->entreprise->name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('offre_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CV / Lettre de motivation --}}
                    <div class="mb-4">
                        <label for="cv" class="form-label fw-semibold">
                            CV / Lettre de motivation <span class="text-danger">*</span>
                        </label>
                        <textarea name="cv" id="cv" rows="7"
                                  class="form-control @error('cv') is-invalid @enderror"
                                  placeholder="Décrivez votre parcours, vos compétences et vos motivations pour ce stage..."
                                  required>{{ old('cv') }}</textarea>
                        @error('cv')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Maximum 5000 caractères.</div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-send me-2"></i>Soumettre ma candidature
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
