@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-4">
    <h2><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</h2>
    <p class="text-muted mb-0">Vue d'ensemble de la plateforme de gestion de stages</p>
</div>

{{-- ======================== STATISTIQUES CLÉS ======================== --}}
<div class="row g-3 g-md-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-primary">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-people-fill text-primary fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">Étudiants</h6>
                    <h3 class="mb-0 fw-bold">{{ $nbEtudiants }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-info">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="bi bi-building text-info fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">Entreprises</h6>
                    <h3 class="mb-0 fw-bold">{{ $nbEntreprises }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card shadow-sm h-100 border-0 border-start border-4 border-success">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 me-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-briefcase-fill text-success fs-4"></i>
                    </div>
                </div>
                <div>
                    <h6 class="text-muted text-uppercase mb-1 small fw-semibold">Offres actives</h6>
                    <h3 class="mb-0 fw-bold">{{ $nbOffresActives }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======================== GRAPHIQUE CANDIDATURES PAR STATUT ======================== --}}
<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Candidatures par statut
        </h5>
    </div>
    <div class="card-body">
        <div class="chart-container" style="position: relative; height: 320px;">
            <canvas id="candidaturesChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('candidaturesChart').getContext('2d');

    const labels = @json($chartLabels);
    const data = @json($chartData);

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nombre de candidatures',
                data: data,
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(25, 135, 84, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgb(255, 193, 7)',
                    'rgb(25, 135, 84)',
                    'rgb(220, 53, 69)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>
@endpush
