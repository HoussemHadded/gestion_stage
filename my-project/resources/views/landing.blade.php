<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gestion des Stages — La plateforme experte connectant étudiants et entreprises.">
    <title>Gestion des Stages — Plateforme d'Internship</title>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (CDN Fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-gray-100 overflow-x-hidden antialiased">
    
    {{-- ══════════════ NAVBAR ══════════════ --}}
    <nav class="fixed w-full z-50 bg-gray-900/80 backdrop-blur-md border-b border-white/10 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ url('/') }}" class="flex items-center text-2xl font-extrabold text-white tracking-tight hover:text-indigo-400 transition">
                    <i class="bi bi-mortarboard-fill mr-3 text-indigo-500"></i>
                    GestionStages
                </a>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-white border border-white/20 rounded-xl hover:bg-white/10 hover:border-white/40 transition">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg hover:from-indigo-500 hover:to-purple-500 transition transform hover:-translate-y-0.5 flex items-center">
                        Commencer <i class="bi bi-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ══════════════ HERO ══════════════ --}}
    <section class="relative min-h-[92vh] flex items-center pt-24 pb-16 overflow-hidden">
        {{-- Background Glow --}}
        <div class="absolute top-0 -left-1/4 w-full h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-indigo-900/40 via-transparent to-transparent pointer-events-none"></div>
        <div class="absolute bottom-0 -right-1/4 w-full h-full bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-purple-900/30 via-transparent to-transparent pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="lg:grid lg:grid-cols-12 lg:gap-16 items-center">
                
                {{-- Left Text --}}
                <div class="lg:col-span-6 text-center lg:text-left mb-16 lg:mb-0">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-semibold mb-8">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Plateforme active 2026
                    </div>
                    
                    <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight mb-6">
                        Trouvez votre<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">stage idéal</span><br>
                        en quelques clics
                    </h1>
                    
                    <p class="text-lg text-gray-400 mb-10 max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                        GestionStages connecte étudiants et entreprises sur une plateforme 
                        professionnelle sécurisée. Publiez des offres, postulez facilement 
                        et gérez vos stages de A à Z.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl hover:shadow-indigo-500/30 hover:scale-105 transition transform flex justify-center items-center">
                            <i class="bi bi-rocket-takeoff mr-2"></i>Commencer gratuitement
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white border border-white/20 bg-white/5 backdrop-blur-md rounded-2xl hover:bg-white/10 hover:border-white/40 transition flex justify-center items-center">
                            <i class="bi bi-box-arrow-in-right mr-2"></i>Se connecter
                        </a>
                    </div>
                    
                    <div class="mt-16 grid grid-cols-3 gap-6 max-w-lg mx-auto lg:mx-0 border-t border-white/10 pt-8">
                        <div>
                            <p class="text-3xl font-black text-white">3</p>
                            <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Rôles distincts</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-white">100%</p>
                            <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Sécurisé</p>
                        </div>
                        <div>
                            <p class="text-3xl font-black text-white">&infin;</p>
                            <p class="text-xs font-semibold text-gray-500 uppercase mt-1">Offres dispo</p>
                        </div>
                    </div>
                </div>
                
                {{-- Right Visual Floating Elements --}}
                <div class="lg:col-span-6 relative h-[500px] hidden lg:block">
                    
                    {{-- Card 1 Admin --}}
                    <div class="absolute top-10 right-10 w-64 bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl animate-[bounce_6s_infinite]">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center text-xl mb-4">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">Panneau Administrateur</h3>
                        <p class="text-xs text-gray-400 mt-1">Vue globale de la sécurité</p>
                        <div class="mt-4 flex gap-2">
                            <span class="px-2 py-1 text-[10px] font-bold rounded bg-red-500/20 text-red-400">5 admins</span>
                            <span class="px-2 py-1 text-[10px] font-bold rounded bg-emerald-500/20 text-emerald-400">Actif</span>
                        </div>
                    </div>
                    
                    {{-- Card 2 Offre --}}
                    <div class="absolute top-1/3 left-0 w-60 bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl animate-[bounce_8s_infinite] [animation-delay:2s]">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl mb-4">
                            <i class="bi bi-briefcase-fill"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">Nouvelle offre publiée</h3>
                        <p class="text-xs text-gray-400 mt-1">Développeur Web &mdash; Paris</p>
                        <div class="mt-4">
                            <span class="px-2 py-1 text-[10px] font-bold rounded bg-amber-500/20 text-amber-400">Stage 6 mois</span>
                        </div>
                    </div>
                    
                    {{-- Card 3 Candidature --}}
                    <div class="absolute bottom-10 right-20 w-56 bg-white/10 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl animate-[bounce_7s_infinite] [animation-delay:4s]">
                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-400 flex items-center justify-center text-xl mb-4">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <h3 class="text-sm font-bold text-white">Candidature acceptée !</h3>
                        <p class="text-xs text-gray-400 mt-1">Félicitations pour le poste</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════ FEATURES ══════════════ --}}
    <section class="py-24 bg-gray-900 border-t border-white/5 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-indigo-400 font-bold tracking-widest text-sm uppercase mb-3">Fonctionnalités</h2>
                <p class="text-3xl md:text-4xl font-extrabold text-white mb-4">Tout ce dont vous avez besoin</p>
                <p class="text-gray-400 text-lg">Une plateforme complète qui centralise la gestion des stages pour toutes les parties prenantes, construite sur base Laravel.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="bg-gray-800/40 border border-white/10 rounded-3xl p-8 hover:-translate-y-2 hover:border-indigo-500/30 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Authentification robuste</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Système de connexion hautement sécurisé avec séparation stricte des données selon le rôle de l'utilisateur.</p>
                </div>
                {{-- Feature 2 --}}
                <div class="bg-gray-800/40 border border-white/10 rounded-3xl p-8 hover:-translate-y-2 hover:border-amber-500/30 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Gestion des Offres</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Les entreprises créent et modifient leurs annonces, et les étudiants naviguent de manière fluide via un tableau dynamique.</p>
                </div>
                {{-- Feature 3 --}}
                <div class="bg-gray-800/40 border border-white/10 rounded-3xl p-8 hover:-translate-y-2 hover:border-emerald-500/30 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Suivi 100% automatisé</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Les candidatures sont associées directement aux offres. Notifications automatiques par email lors des changements de statut.</p>
                </div>
                {{-- Feature 4 --}}
                <div class="bg-gray-800/40 border border-white/10 rounded-3xl p-8 hover:-translate-y-2 hover:border-red-500/30 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-red-500/10 text-red-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="bi bi-speedometer2"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Dashboards Chart.js</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Des graphes dynamiques pour surveiller les candidatures reçues, les acceptations et autres statistiques cruciales de recrutement.</p>
                </div>
                {{-- Feature 5 --}}
                <div class="bg-gray-800/40 border border-white/10 rounded-3xl p-8 hover:-translate-y-2 hover:border-blue-500/30 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Sécurité & Policies</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Validation rigoureuse des requêtes, protection CSRF/XSS et middleware assurant le respect d'accès de chaque rôle (Admin vs Etudiant).</p>
                </div>
                {{-- Feature 6 --}}
                <div class="bg-gray-800/40 border border-white/10 rounded-3xl p-8 hover:-translate-y-2 hover:border-pink-500/30 transition duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-pink-500/10 text-pink-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Mails Transactionnels</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">Design Tailwind intégré dans les emails pour notifier immédiatement l'entreprise d'un nouveau profil candidat.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════ CTA ══════════════ --}}
    <section class="py-24 relative border-t border-b border-white/10 bg-gradient-to-r from-gray-900 via-indigo-900/20 to-gray-900 text-center">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-extrabold text-white mb-4">
                Prêt à lancer votre <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400">recrutement ?</span>
            </h2>
            <p class="text-xl text-gray-400 mb-10">Rejoignez-nous maintenant et simplifiez drastiquement le cycle de stage conventionnel.</p>
            
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-indigo-600 font-bold rounded-xl shadow-lg hover:bg-gray-100 transition transform hover:-translate-y-1 w-full sm:w-auto">
                    <i class="bi bi-person-plus-fill mr-2"></i>Créer un compte
                </a>
                <a href="{{ route('login') }}" class="px-8 py-4 bg-transparent border border-white/30 text-white font-bold rounded-xl hover:bg-white/10 w-full sm:w-auto transition">
                    Se connecter
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════════ FOOTER ══════════════ --}}
    <footer class="bg-gray-900 border-t border-white/5 py-8 text-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 text-gray-500">
            <div class="flex items-center text-white font-bold">
                <i class="bi bi-mortarboard-fill text-indigo-500 mr-2"></i>GestionStages
            </div>
            <div>&copy; {{ date('Y') }} — Plateforme de gestion des stages professionnelle.</div>
            <div class="flex space-x-6">
                <a href="{{ route('login') }}" class="hover:text-white transition">Connexion</a>
                <a href="{{ route('register') }}" class="hover:text-white transition">Inscription</a>
            </div>
        </div>
    </footer>

</body>
</html>
