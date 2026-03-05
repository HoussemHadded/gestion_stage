@extends('layouts.app')

@section('title', 'Modifier l\'utilisateur')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-pencil-square me-2"></i>Modifier l'utilisateur</h2>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nom --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mot de passe (optionnel en édition) --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe <small class="text-muted">(laisser vide pour ne pas changer)</small></label>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror"
                               minlength="6">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Rôle --}}
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle <span class="text-danger">*</span></label>
                        <select name="role" id="role"
                                class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">-- Sélectionner un rôle --</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Étudiant</option>
                            <option value="entreprise" {{ old('role', $user->role) === 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Champs Entreprise (affichés dynamiquement) --}}
                    <div id="entreprise-fields" style="display: none;">
                        <hr>
                        <h6 class="text-muted mb-3"><i class="bi bi-building me-1"></i>Informations Entreprise</h6>

                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nom de l'entreprise</label>
                            <input type="text" name="company_name" id="company_name"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name', $user->company_name) }}">
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="company_address" class="form-label">Adresse de l'entreprise</label>
                            <input type="text" name="company_address" id="company_address"
                                   class="form-control @error('company_address') is-invalid @enderror"
                                   value="{{ old('company_address', $user->company_address) }}">
                            @error('company_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-save me-1"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const roleSelect = document.getElementById('role');
    const entrepriseFields = document.getElementById('entreprise-fields');

    function toggleEntrepriseFields() {
        entrepriseFields.style.display = roleSelect.value === 'entreprise' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleEntrepriseFields);
    toggleEntrepriseFields();
</script>
@endpush
