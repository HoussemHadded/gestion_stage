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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .guest-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 2.5rem;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }
        .brand-logo {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            text-decoration: none;
            letter-spacing: -0.5px;
        }
        .brand-logo:hover { color: #a78bfa; }
        .form-control, .form-select {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            color: #fff;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            background: rgba(255,255,255,0.12);
            border-color: #a78bfa;
            box-shadow: 0 0 0 0.2rem rgba(167,139,250,0.25);
            color: #fff;
            outline: none;
        }
        .form-label { color: rgba(255,255,255,0.8); font-size: 0.875rem; font-weight: 500; }
        .form-select option { background: #302b63; color: #fff; }
        .btn-primary-gradient {
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem;
            width: 100%;
            transition: all 0.3s;
            font-size: 1rem;
        }
        .btn-primary-gradient:hover {
            background: linear-gradient(135deg, #6d28d9, #4338ca);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(124,58,237,0.4);
            color: #fff;
        }
        .page-title { color: #fff; font-weight: 700; font-size: 1.75rem; }
        .page-subtitle { color: rgba(255,255,255,0.6); font-size: 0.9rem; }
        .text-link { color: #a78bfa; text-decoration: none; font-weight: 500; }
        .text-link:hover { color: #c4b5fd; text-decoration: underline; }
        .divider { border-color: rgba(255,255,255,0.1); margin: 1.5rem 0; }
        .invalid-feedback { display: block; font-size: 0.8rem; color: #f87171; }
        .is-invalid { border-color: #f87171 !important; }
        .form-check-input {
            background-color: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.3);
        }
        .form-check-label { color: rgba(255,255,255,0.7); font-size: 0.875rem; }
        .back-home {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }
        .back-home:hover { color: #a78bfa; }
        @stack('styles')
    </style>
</head>
<body>

    <div class="w-100" style="max-width: 460px; margin: 0 auto;">
        {{-- Back to home link --}}
        <div class="mb-3 text-center">
            <a href="{{ url('/') }}" class="back-home">
                <i class="bi bi-arrow-left me-1"></i>Retour à l'accueil
            </a>
        </div>

        <div class="guest-card">
            {{-- Brand --}}
            <div class="text-center mb-4">
                <a href="{{ url('/') }}" class="brand-logo">
                    <i class="bi bi-mortarboard-fill me-2"></i>Gestion de Stages
                </a>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success mb-3 border-0" style="background:rgba(16,185,129,0.15);color:#6ee7b7;border-radius:10px;">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mb-3 border-0" style="background:rgba(239,68,68,0.15);color:#fca5a5;border-radius:10px;">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                </div>
            @endif

            {{-- Page Content --}}
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
