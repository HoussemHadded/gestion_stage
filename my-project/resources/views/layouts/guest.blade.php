<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion de Stages')</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN Fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-gray-900 flex items-center justify-center p-6 text-gray-100">

    <div class="w-full max-w-md">
        {{-- Back to home link --}}
        <div class="mb-4 text-center">
            <a href="{{ url('/') }}" class="text-gray-400 hover:text-indigo-300 transition flex items-center justify-center text-sm font-medium">
                <i class="bi bi-arrow-left mr-1"></i>Retour à l'accueil
            </a>
        </div>

        <div class="bg-white/10 backdrop-blur-xl border border-white/20 shadow-2xl rounded-2xl p-8 relative overflow-hidden">
            {{-- Brand --}}
            <div class="text-center mb-8 relative z-10">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-white tracking-tight hover:text-indigo-300 transition flex items-center justify-center">
                    <i class="bi bi-mortarboard-fill mr-2 text-indigo-400"></i>
                    Gestion de Stages
                </a>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 px-4 py-3 rounded-lg text-sm flex items-center relative z-10">
                    <i class="bi bi-check-circle mr-2 text-lg"></i>{{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-500/20 border border-red-500/50 text-red-300 px-4 py-3 rounded-lg text-sm flex items-center relative z-10">
                    <i class="bi bi-exclamation-triangle mr-2 text-lg"></i>{{ session('error') }}
                </div>
            @endif

            {{-- Page Content --}}
            <div class="relative z-10">
                @yield('content')
            </div>
            
            {{-- Decorative glow effect --}}
            <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-indigo-500/20 blur-3xl rounded-full pointer-events-none"></div>
            <div class="absolute -top-24 -left-24 w-48 h-48 bg-purple-500/20 blur-3xl rounded-full pointer-events-none"></div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form:not([data-no-loader])').forEach(form => {
                form.addEventListener('submit', function(e) {
                    if (this.dataset.loading === 'true') {
                        e.preventDefault();
                        return;
                    }
                    this.dataset.loading = 'true';
                    let btn = this.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.dataset.originalHtml = btn.innerHTML;
                        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-2"></i>Veuillez patienter...';
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
