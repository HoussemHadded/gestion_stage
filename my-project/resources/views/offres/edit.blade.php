@extends('layouts.app')

@section('title', 'Modifier l\'offre')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-pencil-square me-2"></i>Modifier l'Offre</h2>
            <a href="{{ route('offres.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('offres.update', $offre->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Titre --}}
                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre de l'offre <span class="text-danger">*</span></label>
                        <input type="text" name="titre" id="titre"
                               class="form-control @error('titre') is-invalid @enderror"
                               value="{{ old('titre', $offre->titre) }}" required>
                        @error('titre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  required>{{ old('description', $offre->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date de publication --}}
                    <div class="mb-3">
                        <label for="date_publication" class="form-label">Date de publication <span class="text-danger">*</span></label>
                        <input type="date" name="date_publication" id="date_publication"
                               class="form-control @error('date_publication') is-invalid @enderror"
                               value="{{ old('date_publication', $offre->date_publication) }}" required>
                        @error('date_publication')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Entreprise (visible uniquement pour l'admin) --}}
                    @if(auth()->user()->role === 'admin')
                    <div class="mb-3">
                        <label for="entreprise_id" class="form-label">Entreprise <span class="text-danger">*</span></label>
                        <select name="entreprise_id" id="entreprise_id"
                                class="form-select @error('entreprise_id') is-invalid @enderror" required>
                            <option value="">-- Sélectionner une entreprise --</option>
                            @foreach($entreprises as $entreprise)
                                <option value="{{ $entreprise->id }}"
                                    {{ old('entreprise_id', $offre->entreprise_id) == $entreprise->id ? 'selected' : '' }}>
                                    {{ $entreprise->name }} {{ $entreprise->company_name ? '(' . $entreprise->company_name . ')' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('entreprise_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save me-1"></i>Mettre à jour l'offre
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
