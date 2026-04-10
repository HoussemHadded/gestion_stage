<!DOCTYPE html>
<html lang="fr" class="bg-gray-50 h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gestion de Stages')</title>

    <!-- Bootstrap Icons (Keeping purely for icons since they are used widely) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN Fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>

    @stack('styles')
</head>
<body class="h-full flex flex-col font-sans text-gray-800 antialiased">

    {{-- ======================== NAVBAR ======================== --}}
    <nav class="bg-gray-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Brand --}}
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center text-lg font-bold tracking-wider hover:text-indigo-400 transition">
                        <i class="bi bi-mortarboard-fill mr-2 text-indigo-500"></i>
                        Gestion de Stages
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                            <i class="bi bi-house-door mr-1"></i>Accueil
                        </a>

                        {{-- Admin --}}
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-speedometer2 mr-1"></i>Dashboard
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-people mr-1"></i>Utilisateurs
                            </a>
                            <a href="{{ route('admin.offres.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-briefcase mr-1"></i>Offres
                            </a>
                            <a href="{{ route('admin.candidatures.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-file-earmark-text mr-1"></i>Candidatures
                            </a>
                        @endif

                        {{-- Entreprise --}}
                        @if(auth()->user()->isEntreprise())
                            <a href="{{ route('entreprise.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-speedometer2 mr-1"></i>Dashboard
                            </a>
                            <a href="{{ route('entreprise.offres.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-briefcase mr-1"></i>Mes offres
                            </a>
                            <a href="{{ route('entreprise.candidatures.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-file-earmark-text mr-1"></i>Candidatures
                            </a>
                        @endif

                        {{-- Étudiant --}}
                        @if(auth()->user()->isStudent())
                            <a href="{{ route('student.dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-speedometer2 mr-1"></i>Dashboard
                            </a>
                            <a href="{{ route('student.offres.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-briefcase mr-1"></i>Offres
                            </a>
                            <a href="{{ route('student.candidatures.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
                                <i class="bi bi-folder2-open mr-1"></i>Mes Candidatures
                            </a>
                        @endif

                        {{-- Profile / Logout --}}
                        <div class="ml-4 flex items-center border-l border-gray-700 pl-4 space-x-4">
                            <span class="text-sm font-medium text-gray-300">
                                <i class="bi bi-person-circle mr-1"></i>{{ auth()->user()->name }}
                                <span class="ml-1 text-xs px-2 py-1 bg-gray-800 text-indigo-300 rounded-full">{{ auth()->user()->role->label() }}</span>
                            </span>
                            
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-red-400 hover:text-red-300 transition">
                                    <i class="bi bi-box-arrow-right mr-1"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-3 py-2 rounded-md text-sm font-medium bg-indigo-600 hover:bg-indigo-500 transition shadow">
                            <i class="bi bi-box-arrow-in-right mr-1"></i>Connexion
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ======================== FLASH MESSAGES ======================== --}}
    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-md shadow-sm">
                <div class="flex items-center">
                    <i class="bi bi-check-circle-fill text-green-500 mr-3 text-lg"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md shadow-sm">
                <div class="flex items-center">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 mr-3 text-lg"></i>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md shadow-sm">
                <div class="flex items-start">
                    <i class="bi bi-exclamation-triangle-fill text-red-500 mr-3 mt-0.5 text-lg"></i>
                    <div>
                        <strong class="text-red-800 font-medium block mb-1">Erreurs de validation :</strong>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- ======================== PAGE CONTENT ======================== --}}
        @yield('content')

    </main>

    {{-- ======================== FOOTER ======================== --}}
    <footer class="bg-gray-900 text-gray-400 py-6 mt-12 w-full text-center text-sm shadow-inner mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            &copy; {{ date('Y') }} Gestion de Stages &mdash; Tous droits réservés.
        </div>
    </footer>

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
                        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin mr-2"></i>Chargement...';
                        btn.classList.add('opacity-75', 'cursor-not-allowed');
                    }
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
