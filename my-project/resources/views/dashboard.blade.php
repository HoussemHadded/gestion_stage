@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="mb-3">
                    <i class="bi bi-speedometer2 me-2"></i>Tableau de bord
                </h2>

                <p class="lead mb-3">
                    Bonjour {{ $user->name }}, vous êtes connecté en tant que
                    <strong>{{ $user->role->label() }}</strong>.
                </p>

                <p class="mb-0">
                    Utilisez la barre de navigation pour accéder aux fonctionnalités adaptées à votre profil.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

