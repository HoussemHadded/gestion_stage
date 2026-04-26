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

    <!-- Font, Alpine & Chart.js -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
      body { font-family: 'Inter', sans-serif; }
      [x-cloak] { display: none !important; }
      /* SaaS Glassmorphism & Animations */
      .glass-card { 
          background: rgba(255, 255, 255, 0.95); 
          backdrop-filter: blur(10px); 
          border: 1px solid rgba(255, 255, 255, 0.3); 
          box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); 
      }
      .dark .glass-card { background: rgba(17, 24, 39, 0.85); border: 1px solid rgba(255, 255, 255, 0.05); }
      .premium-hover { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
      .premium-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 20px -3px rgba(79, 70, 229, 0.15), 0 4px 6px -2px rgba(79, 70, 229, 0.05); border-color: rgba(79, 70, 229, 0.3); }
      .sk-loading { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; background-color: #f3f4f6; color: transparent !important; }
      .dark .sk-loading { background-color: #374151; }
      @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
      .chart-container { position: relative; height: 300px; width: 100%; }
    </style>

    @stack('styles')
</head>
<body class="h-full flex flex-col font-sans text-gray-800 antialiased">

    {{-- ======================== NAVBAR ======================== --}}
    <nav class="bg-gray-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Brand --}}
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center text-lg font-bold tracking-wider hover:text-indigo-400 transition">
                        <i class="bi bi-mortarboard-fill mr-2 text-indigo-500"></i>
                        Gestion de Stages
                    </a>
                </div>

                {{-- Desktop Menu --}}
                <div class="hidden sm:flex sm:items-center sm:space-x-4">
                    @auth
                        <a href="{{ route('home') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition">
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
                            <a href="{{ route('entreprise.candidats.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition text-yellow-400">
                                <i class="bi bi-robot mr-1"></i>Candidats (IA)
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
                            <a href="{{ route('student.match.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition text-yellow-400">
                                <i class="bi bi-robot mr-1"></i>AI Matching
                            </a>
                            <a href="{{ route('student.cv.show') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition text-yellow-400">
                                <i class="bi bi-file-earmark-person mr-1"></i>Mon CV (IA)
                            </a>
                        @endif

                        {{-- Global Leaderboard (Accessible to all authenticated) --}}
                        <div class="ml-2 border-l border-gray-700 pl-2">
                            <a href="{{ route('ranking.students') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-800 hover:text-indigo-300 transition text-indigo-400">
                                <i class="bi bi-trophy mr-1"></i>Classement
                            </a>
                        </div>

                        {{-- Notifications & Profile / Logout --}}
                        <div class="ml-4 flex items-center border-l border-gray-700 pl-4 space-x-4">
                            
                            {{-- Notification Bell --}}
                            <div class="relative" x-data="{ open: false }">
                                <button id="notif-btn" @click="open = !open" class="text-gray-300 hover:text-white transition relative focus:outline-none">
                                    <i class="bi bi-bell-fill text-xl"></i>
                                    @php
                                        $unreadCount = auth()->user()->unreadNotifications->count();
                                    @endphp
                                    <span id="notif-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $unreadCount == 0 ? 'hidden' : '' }}">
                                        {{ $unreadCount }}
                                    </span>
                                </button>

                                {{-- Dropdown --}}
                                <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                    <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                        <h3 class="text-sm font-bold text-gray-800">Notifications</h3>
                                        <button id="mark-all-read" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Tout marquer comme lu</button>
                                    </div>
                                    <div id="notif-list" class="max-h-64 overflow-y-auto">
                                        @forelse(auth()->user()->notifications()->take(5)->get() as $notif)
                                            <a href="{{ $notif->data['url'] ?? '#' }}" data-id="{{ $notif->id }}" class="notif-item block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition {{ empty($notif->read_at) ? 'bg-indigo-50' : '' }}">
                                                <p class="text-sm text-gray-800 font-medium">{{ $notif->data['message'] ?? 'Nouvelle notification' }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                            </a>
                                        @empty
                                            <div class="px-4 py-4 text-center text-sm text-gray-500" id="empty-notif">Aucune notification.</div>
                                        @endforelse
                                    </div>
                                    <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-center text-xs font-bold text-gray-600 hover:text-indigo-600 border-t border-gray-100 bg-gray-50">
                                        Voir toutes les notifications
                                    </a>
                                </div>
                            </div>

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

    <!-- AlpineJS for Dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Pusher & Echo (CDN Fallback since npm is missing) -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-20 right-5 z-50 flex flex-col space-y-2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userId = {{ auth()->id() ?? 'null' }};
            
            // 1. Setup Echo dynamically
            if(userId && window.Echo !== undefined) {
                window.Echo = new Echo({
                    broadcaster: 'pusher',
                    key: '{{ env('PUSHER_APP_KEY', 'app-key') }}',
                    cluster: '{{ env('PUSHER_APP_CLUSTER', 'mt1') }}',
                    wsHost: '{{ env('REVERB_HOST') }}' || window.location.hostname,
                    wsPort: {{ env('REVERB_PORT', 8080) }},
                    wssPort: {{ env('REVERB_PORT', 443) }},
                    forceTLS: false, // For standard local reverb
                    disableStats: true,
                    enabledTransports: ['ws', 'wss'],
                });

                // Listen to private channel
                window.Echo.private('App.Models.User.' + userId)
                    .notification((notification) => {
                        
                        // 1. Update Badge
                        let badge = document.getElementById('notif-badge');
                        badge.classList.remove('hidden');
                        badge.innerText = parseInt(badge.innerText || 0) + 1;
                        
                        // 2. Add to list Dropdown
                        let list = document.getElementById('notif-list');
                        let emptyMsg = document.getElementById('empty-notif');
                        if (emptyMsg) emptyMsg.remove();
                        
                        let newItem = `<a href="${notification.url || '#'}" data-id="${notification.id}" class="notif-item block px-4 py-3 border-b border-gray-50 bg-indigo-50 hover:bg-gray-50 transition">
                                <p class="text-sm text-gray-800 font-medium">${notification.message}</p>
                                <p class="text-xs text-gray-500 mt-1">À l'instant</p>
                            </a>`;
                        
                        list.insertAdjacentHTML('afterbegin', newItem);
                        
                        // 3. Show Toast Popup
                        showToast(notification.message);
                    });
            }

            // Toast Logic
            function showToast(message) {
                const container = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = 'bg-gray-900 border-l-4 border-indigo-500 text-white px-6 py-4 rounded shadow-2xl flex items-center transform transition-all duration-500 translate-x-full opacity-0';
                toast.innerHTML = `
                    <i class="bi bi-info-circle text-xl mr-3 text-indigo-400"></i>
                    <div>
                        <p class="font-bold text-sm">Nouvelle Notification</p>
                        <p class="text-xs text-gray-300 mt-1">${message}</p>
                    </div>
                `;
                container.appendChild(toast);
                
                // Animate In
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                    toast.classList.add('translate-x-0', 'opacity-100');
                }, 10);
                
                // Animate Out
                setTimeout(() => {
                    toast.classList.remove('translate-x-0', 'opacity-100');
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 500);
                }, 4000);
            }

            // AJAX Mark Read logic
            document.body.addEventListener('click', function(e) {
                // Click on "Mark All Read"
                if (e.target.id === 'mark-all-read') {
                    e.preventDefault();
                    fetch('{{ route('notifications.markAllRead') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json'
                        }
                    }).then(r => r.json()).then(data => {
                        document.getElementById('notif-badge').classList.add('hidden');
                        document.getElementById('notif-badge').innerText = '0';
                        document.querySelectorAll('.notif-item').forEach(el => el.classList.remove('bg-indigo-50'));
                    });
                }
                
                // Click on specific notification item
                let item = e.target.closest('.notif-item');
                if (item && item.classList.contains('bg-indigo-50')) {
                    e.preventDefault(); // Stop instant navigation
                    let id = item.getAttribute('data-id');
                    let targetUrl = item.getAttribute('href');
                    
                    if(id) {
                        fetch(`/notifications/${id}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json'
                            }
                        }).then(() => {
                            // After marking read, redirect user natively
                            if(targetUrl && targetUrl !== '#') window.location.href = targetUrl;
                        });
                        
                        // Optimistic UI Update
                        item.classList.remove('bg-indigo-50');
                        let badge = document.getElementById('notif-badge');
                        let count = parseInt(badge.innerText) - 1;
                        if(count > 0) {
                            badge.innerText = count;
                        } else {
                            badge.classList.add('hidden');
                            badge.innerText = '0';
                        }
                    }
                }
            });
        });
    </script>

    @stack('scripts')

    {{-- ======================== SMART ASSISTANT IA ======================== --}}
    @auth
    <div id="smart-assistant" class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-[9999] font-sans" x-data="{ open: false }">
        
        <!-- Chat Button -->
        <button id="sa-toggle-btn" @click="open = !open" 
                class="bg-gradient-to-tr from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-full h-14 w-14 flex items-center justify-center shadow-[0_8px_30px_rgb(79,70,229,0.4)] transform transition-transform duration-300 hover:scale-110 focus:outline-none group border border-white/10">
            <i class="bi bi-chat-dots-fill text-2xl transition-transform duration-300 group-hover:rotate-12" x-show="!open"></i>
            <i class="bi bi-x-lg text-2xl font-bold" x-show="open" x-cloak></i>
        </button>

        <!-- Chat Window -->
        <div x-cloak x-show="open" 
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-300 transform origin-bottom-right"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200 transform origin-bottom-right"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="fixed bottom-0 right-0 w-full h-[85vh] sm:h-[90vh] md:absolute md:bottom-20 md:right-0 md:w-[380px] md:h-[500px] bg-gray-900 border-t border-gray-800 md:border md:rounded-2xl shadow-2xl flex flex-col overflow-hidden z-50 rounded-t-3xl md:rounded-t-2xl">
            
            <!-- Sticky Header -->
            <div class="shrink-0 bg-gray-800 p-4 flex items-center justify-between border-b border-gray-700/80 shadow-sm relative z-20">
                <div class="flex items-center">
                    <div class="relative">
                        <div class="bg-gray-900 border border-gray-700 p-2 rounded-xl mr-3 shadow-inner">
                            <i class="bi bi-robot text-xl text-indigo-400"></i>
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-3 h-3 bg-emerald-400 border-2 border-gray-800 rounded-full animate-pulse"></span>
                    </div>
                    <div>
                        <h3 class="font-bold text-sm text-white tracking-wide">Coach IA</h3>
                        <p class="text-[11px] text-gray-400 font-medium">Toujours là pour vous aider</p>
                    </div>
                </div>
                <div class="flex items-center space-x-1">
                    <button @click="open = false" class="text-gray-400 hover:text-white transition-colors p-1.5 rounded-lg hover:bg-gray-700 focus:outline-none">
                        <i class="bi bi-dash-lg text-lg"></i>
                    </button>
                    <button @click="open = false" class="text-gray-400 hover:text-red-400 transition-colors p-1.5 rounded-lg hover:bg-gray-700 focus:outline-none md:hidden">
                        <i class="bi bi-x-lg text-lg"></i>
                    </button>
                </div>
            </div>

            <!-- Messages Area (Scrollable) -->
            <div id="sa-messages" class="flex-1 overflow-y-auto p-4 bg-gray-900 flex flex-col space-y-4 relative z-10 scroll-smooth scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-transparent">
                <!-- Messages injected here -->
            </div>

            <!-- Typing Indicator -->
            <div id="sa-typing" class="hidden px-4 pb-3 bg-gray-900 shrink-0">
                <div class="flex items-center text-xs font-bold text-gray-500 tracking-widest uppercase">
                    L'IA écrit<span class="flex ml-1.5 space-x-1"><span class="w-1 h-1 bg-gray-500 rounded-full animate-bounce"></span><span class="w-1 h-1 bg-gray-500 rounded-full animate-bounce delay-100"></span><span class="w-1 h-1 bg-gray-500 rounded-full animate-bounce delay-200"></span></span>
                </div>
            </div>

            <!-- Sticky Input Area -->
            <div class="shrink-0 bg-gray-800 border-t border-gray-700/80 p-3 flex flex-col space-y-2 relative z-20">
                <div id="sa-suggestions" class="flex flex-wrap gap-1.5 pb-1"></div>
                <form id="sa-form" class="flex items-center relative gap-2 w-full">
                    <input type="text" id="sa-input" required autocomplete="off"
                           class="w-full bg-gray-900 border border-gray-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-sm font-medium rounded-xl py-3 pl-4 pr-12 text-white placeholder-gray-500 transition-all outline-none shadow-inner"
                           placeholder="Posez votre question...">
                    <button type="submit" class="absolute right-1.5 top-1.5 bottom-1.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg w-9 flex items-center justify-center transition-colors shadow-sm focus:outline-none">
                        <i class="bi bi-send-fill text-sm"></i>
                    </button>
                </form>
                <div class="text-center text-[9px] text-gray-500 tracking-widest uppercase font-bold">
                    Propulsé par le Moteur IA
                </div>
            </div>
        </div>
    </div>

    <!-- Assistant Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('sa-form');
            if(!form) return;
            const input = document.getElementById('sa-input');
            const messages = document.getElementById('sa-messages');
            const typing = document.getElementById('sa-typing');
            let hasWelcomed = false;

            const encodeHTML = (str) => {
                let p = document.createElement("p");
                p.textContent = str;
                return p.innerHTML;
            };

            const scrollToBottom = () => {
                // Ensure scroll reaches the very bottom
                requestAnimationFrame(() => {
                    messages.scrollTo({
                        top: messages.scrollHeight,
                        behavior: 'smooth'
                    });
                });
            };

            const addMessage = (text, isUser = false) => {
                const div = document.createElement('div');
                div.className = `flex items-start ${isUser ? 'justify-end' : ''} animate-fade-in-up w-full`;
                const safeText = isUser ? encodeHTML(text) : text;
                
                if (isUser) {
                    div.innerHTML = `
                        <div class="ml-12 bg-indigo-600 p-3 rounded-2xl rounded-tr-sm shadow-sm text-[13px] font-medium text-white break-words leading-relaxed border border-indigo-500/50">
                             ${safeText}
                        </div>
                    `;
                } else {
                    div.innerHTML = `
                        <div class="flex-shrink-0 bg-gray-800 rounded-xl h-8 w-8 flex items-center justify-center shadow-sm mt-1 border border-gray-700">
                            <i class="bi bi-robot text-indigo-400 text-xs"></i>
                        </div>
                        <div class="mr-12 ml-2 bg-gray-800 p-3 rounded-2xl rounded-tl-sm border border-gray-700 shadow-sm text-[13px] font-medium text-gray-200 break-words leading-relaxed">
                            ${safeText}
                        </div>
                    `;
                }
                
                messages.appendChild(div);
                scrollToBottom();
            };

            document.getElementById('sa-toggle-btn').addEventListener('click', function() {
                if (!hasWelcomed) {
                    hasWelcomed = true;
                    setTimeout(() => {
                        typing.classList.remove('hidden');
                        scrollToBottom();
                        setTimeout(() => {
                            typing.classList.add('hidden');
                            const userName = <?php echo json_encode(auth()->check() ? auth()->user()->name : ''); ?>;
                            const welcomeMsg = `Bonjour <strong>${encodeHTML(userName)}</strong> ! 👋 Je suis votre assistant virtuel. Comment puis-je vous aider aujourd'hui ?`;
                            addMessage(welcomeMsg, false);
                        }, 800);
                    }, 300);
                }
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const text = input.value.trim();
                if (!text) return;

                addMessage(text, true);
                input.value = '';
                input.disabled = true;
                
                typing.classList.remove('hidden');
                scrollToBottom();

                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

                fetch('/assistant/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: text,
                        url: window.location.pathname
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    const delay = Math.min(Math.max((data.reply || '').length * 15, 600), 1500);
                    setTimeout(() => {
                        typing.classList.add('hidden');
                        addMessage(data.reply ? encodeHTML(data.reply) : 'Désolé, je ne sais pas quoi répondre.', false);
                        input.disabled = false;
                        input.focus();
                    }, delay);
                })
                .catch(error => {
                    setTimeout(() => {
                        typing.classList.add('hidden');
                        addMessage("Désolé, une erreur technique m'empêche de vous répondre. Réessayez plus tard.", false);
                        input.disabled = false;
                        input.focus();
                    }, 500);
                });
            });

            // --- Suggestions Logic ---
            const suggestionsData = {
                'cv': [
                    "Comment améliorer mon CV ?",
                    "Quelles compétences ajouter ?",
                    "Corriger mon CV"
                ],
                'candidatures': [
                    "Suivre mes candidatures",
                    "Comment relancer une entreprise ?",
                    "Conseils entretien"
                ],
                'match': [
                    "Explique mon score Match",
                    "Comment atteindre 90% ?",
                    "Entreprises recommandées"
                ],
                'default': [
                    "Je cherche un stage",
                    "Entreprises recommandées",
                    "Conseils pour mon stage"
                ]
            };

            const renderSuggestions = () => {
                const path = window.location.pathname.toLowerCase();
                let list = suggestionsData['default'];
                
                if (path.includes('/cv') || path.includes('/profile')) list = suggestionsData['cv'];
                else if (path.includes('/candidature')) list = suggestionsData['candidatures'];
                else if (path.includes('/match') || path.includes('/offre')) list = suggestionsData['match'];
                
                const container = document.getElementById('sa-suggestions');
                if(!container) return;
                
                container.innerHTML = '';
                list.forEach(text => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'px-3 py-1.5 text-[11px] font-medium tracking-wide bg-gray-700/40 hover:bg-gray-700 text-gray-300 hover:text-white transition-colors rounded-full text-left leading-tight border border-gray-600/30';
                    btn.innerText = text;
                    btn.onclick = () => {
                        input.value = text;
                        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                        container.style.opacity = '0';
                        setTimeout(() => container.style.display = 'none', 300);
                    };
                    container.appendChild(btn);
                });
            };

            renderSuggestions();
        });
    </script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        #sa-suggestions { transition: opacity 0.3s ease; }
    </style>
    @endauth
</body>
</html>
