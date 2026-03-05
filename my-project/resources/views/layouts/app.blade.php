<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion de Stages')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f6f9;
        }
        main { flex: 1; }
        .navbar-brand { font-weight: 700; letter-spacing: 1px; }
        .table th { background-color: #343a40; color: #fff; }
        .btn-action { margin-right: 4px; }
        footer { background-color: #343a40; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- ======================== NAVBAR ======================== --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-mortarboard-fill me-2"></i>Gestion de Stages
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto">

                    @auth
                        {{-- Liens communs à tous les utilisateurs connectés --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">
                                <i class="bi bi-house-door me-1"></i>Accueil
                            </a>
                        </li>

                        {{-- Admin --}}
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">
                                    <i class="bi bi-people me-1"></i>Utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('offres.index') }}">
                                    <i class="bi bi-briefcase me-1"></i>Offres
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('candidatures.index') }}">
                                    <i class="bi bi-file-earmark-text me-1"></i>Candidatures
                                </a>
                            </li>
                        @endif

                        {{-- Entreprise --}}
                        @if(auth()->user()->role === 'entreprise')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('offres.index') }}">
                                    <i class="bi bi-briefcase me-1"></i>Offres
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('candidatures.index') }}">
                                    <i class="bi bi-file-earmark-text me-1"></i>Candidatures
                                </a>
                            </li>
                        @endif

                        {{-- Étudiant --}}
                        @if(auth()->user()->role === 'student')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('offres.index') }}">
                                    <i class="bi bi-briefcase me-1"></i>Offres
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('candidatures.create') }}">
                                    <i class="bi bi-pencil-square me-1"></i>Postuler
                                </a>
                            </li>
                        @endif

                        {{-- Utilisateur connecté + Logout --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text text-muted">
                                        Rôle : <strong>{{ ucfirst(auth()->user()->role) }}</strong>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ url('/api/logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/api/login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Connexion
                            </a>
                        </li>
                    @endauth

                </ul>
            </div>
        </div>
    </nav>

    {{-- ======================== FLASH MESSAGES ======================== --}}
    <main class="container py-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="bi bi-exclamation-triangle me-2"></i>Erreurs de validation :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ======================== PAGE CONTENT ======================== --}}
        @yield('content')

    </main>

    {{-- ======================== FOOTER ======================== --}}
    <footer class="text-white text-center py-3 mt-auto">
        <div class="container">
            <small>&copy; {{ date('Y') }} Gestion de Stages &mdash; Tous droits réservés.</small>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
