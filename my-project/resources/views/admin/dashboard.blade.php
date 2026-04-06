@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h2>
    <p class="text-muted mb-0">Vue d'ensemble de la plateforme de gestion de stages</p>
</div>

{{-- ======================== STATISTIQUES CLÉS — LIGNE 1 ======================== --}}
<div class="row g-3 mb-4">

    {{-- Utilisateurs --}}
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-primary">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-people-fill text-primary fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">Utilisateurs</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_users }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Étudiants --}}
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-info">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="bi bi-mortarboard-fill text-info fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">Étudiants</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_students }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Entreprises --}}
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-warning">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-building text-warning fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">Entreprises</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_entreprises }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Candidatures totales --}}
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-secondary">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                        <i class="bi bi-file-earmark-text-fill text-secondary fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">Candidatures</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_candidatures }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======================== STATISTIQUES CLÉS — LIGNE 2 ======================== --}}
<div class="row g-3 mb-4">

    {{-- Offres --}}
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-success">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-briefcase-fill text-success fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">Offres</h6>
                    <h3 class="mb-0 fw-bold">{{ $total_offres }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Candidatures acceptées --}}
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-success">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">Acceptées</h6>
                    <h3 class="mb-0 fw-bold">{{ $accepted_candidatures }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Candidatures en attente --}}
    <div class="col-6 col-lg-3">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-warning">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-hourglass-split text-warning fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">En attente</h6>
                    <h3 class="mb-0 fw-bold">{{ $pending_candidatures }}</h3>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ======================== GRAPHIQUE + CANDIDATURES RÉCENTES ======================== --}}
<div class="row g-4 mb-4">

    {{-- Bar chart --}}
    <div class="col-lg-5">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Candidatures par statut
                </h5>
            </div>
            <div class="card-body d-flex align-items-center">
                <div class="chart-container w-100" style="position:relative;height:260px;">
                    <canvas id="candidaturesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Candidatures récentes --}}
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-file-earmark-text me-2 text-primary"></i>Candidatures récentes
                </h5>
                <a href="{{ route('admin.offres.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 small">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Offre</th>
                                <th class="text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_candidatures as $c)
                                <tr>
                                    <td>{{ $c->student->name ?? '—' }}</td>
                                    <td>{{ $c->offre->titre ?? '—' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $c->statut->badgeClass() }}">
                                            {{ $c->statut->label() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Aucune candidature.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======================== UTILISATEURS RÉCENTS ======================== --}}
<div class="card shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-people me-2 text-primary"></i>Utilisateurs récents
        </h5>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 small">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Inscription</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $user->role->label() }}</span>
                            </td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">Aucun utilisateur.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('candidaturesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Candidatures',
                data: @json($chartData),
                backgroundColor: [
                    'rgba(255, 193, 7,  0.8)',
                    'rgba(25,  135, 84, 0.8)',
                    'rgba(220, 53,  69, 0.8)'
                ],
                borderColor: [
                    'rgb(255, 193, 7)',
                    'rgb(25,  135, 84)',
                    'rgb(220, 53,  69)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
});
</script>
@endpush
