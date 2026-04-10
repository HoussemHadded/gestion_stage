<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Gestion des Stages — La plateforme professionnelle qui connecte étudiants et entreprises pour des stages de qualité.">
    <title>Gestion des Stages — Plateforme d'Internship</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary:   #7c3aed;
            --primary-light: #a78bfa;
            --secondary: #4f46e5;
            --accent:    #f59e0b;
            --dark:      #0f0c29;
            --dark-mid:  #1e1b4b;
            --text-muted: rgba(255,255,255,0.65);
        }

        * { font-family: 'Inter', sans-serif; }

        body {
            background: var(--dark);
            color: #fff;
            overflow-x: hidden;
        }

        /* ── NAVBAR ─────────────────────────────── */
        .navbar-landing {
            background: rgba(15,12,41,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand-text {
            font-weight: 800;
            font-size: 1.25rem;
            color: #fff;
            letter-spacing: -0.5px;
            text-decoration: none;
        }
        .navbar-brand-text:hover { color: var(--primary-light); }
        .nav-btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .nav-btn-outline {
            border: 1.5px solid rgba(255,255,255,0.25);
            color: #fff;
            background: transparent;
        }
        .nav-btn-outline:hover {
            border-color: var(--primary-light);
            color: var(--primary-light);
        }
        .nav-btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            color: #fff;
        }
        .nav-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(124,58,237,0.4);
            color: #fff;
        }

        /* ── HERO ───────────────────────────────── */
        .hero-section {
            min-height: 92vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 5rem 0;
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% -20%, rgba(124,58,237,0.35) 0%, transparent 60%),
                radial-gradient(ellipse 60% 40% at 80% 80%, rgba(79,70,229,0.2) 0%, transparent 60%),
                linear-gradient(180deg, var(--dark) 0%, var(--dark-mid) 100%);
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(167,139,250,0.15);
            border: 1px solid rgba(167,139,250,0.3);
            border-radius: 100px;
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--primary-light);
            margin-bottom: 1.5rem;
            letter-spacing: 0.5px;
        }
        .hero-title {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1.5px;
            margin-bottom: 1.5rem;
        }
        .gradient-text {
            background: linear-gradient(135deg, #a78bfa, #818cf8, #60a5fa);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-desc {
            font-size: 1.15rem;
            color: var(--text-muted);
            line-height: 1.75;
            max-width: 540px;
            margin-bottom: 2.5rem;
        }
        .hero-cta-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .btn-hero-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.875rem 2rem;
            transition: all 0.3s;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(124,58,237,0.3);
        }
        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(124,58,237,0.5);
            color: #fff;
        }
        .btn-hero-outline {
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 12px;
            color: #fff;
            font-weight: 600;
            font-size: 1rem;
            padding: 0.875rem 2rem;
            transition: all 0.3s;
            text-decoration: none;
            backdrop-filter: blur(4px);
        }
        .btn-hero-outline:hover {
            background: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.4);
            color: #fff;
            transform: translateY(-1px);
        }
        .hero-stats {
            display: flex;
            gap: 2.5rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }
        .stat-item { }
        .stat-number {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }
        .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            font-weight: 500;
        }

        /* Hero floating cards */
        .hero-visual {
            position: relative;
            height: 460px;
        }
        .float-card {
            position: absolute;
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 1.25rem 1.5rem;
            animation: float 6s ease-in-out infinite;
        }
        .float-card-1 {
            top: 20px; right: 20px; width: 260px;
            animation-delay: 0s;
        }
        .float-card-2 {
            top: 200px; right: 0; width: 240px;
            animation-delay: 2s;
        }
        .float-card-3 {
            bottom: 20px; right: 60px; width: 220px;
            animation-delay: 4s;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
        }
        .float-card-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 0.75rem;
        }
        .float-card-title { font-size: 0.85rem; font-weight: 600; color: #fff; }
        .float-card-sub { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; }
        .dot-pulse {
            width: 8px; height: 8px; border-radius: 50%;
            background: #10b981;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

        /* ── FEATURES ──────────────────────────── */
        .features-section {
            padding: 6rem 0;
            background: linear-gradient(180deg, var(--dark-mid) 0%, var(--dark) 100%);
            position: relative;
        }
        .section-label {
            color: var(--primary-light);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }
        .section-title {
            font-size: clamp(1.75rem, 3vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -1px;
            color: #fff;
        }
        .section-desc { color: var(--text-muted); font-size: 1.05rem; max-width: 520px; line-height: 1.7; }
        .feature-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.35s;
            position: relative;
            overflow: hidden;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(124,58,237,0.08), transparent);
            opacity: 0;
            transition: opacity 0.35s;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            border-color: rgba(167,139,250,0.3);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.25rem;
        }
        .feature-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 0.5rem; }
        .feature-desc { color: var(--text-muted); font-size: 0.9rem; line-height: 1.65; }

        /* ── ROLES ─────────────────────────────── */
        .roles-section {
            padding: 6rem 0;
            background: var(--dark);
        }
        .role-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.35s;
            text-align: center;
        }
        .role-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 48px rgba(0,0,0,0.3);
        }
        .role-avatar {
            width: 72px; height: 72px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.75rem;
            margin: 0 auto 1.25rem;
        }
        .role-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; }
        .role-desc { color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem; line-height: 1.65; }
        .role-features { list-style: none; padding: 0; margin: 0; text-align: left; }
        .role-features li {
            color: var(--text-muted);
            font-size: 0.875rem;
            padding: 0.35rem 0;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .role-features li i { margin-top: 2px; }

        /* ── CTA SECTION ────────────────────────── */
        .cta-section {
            padding: 6rem 0;
            background: linear-gradient(135deg, rgba(124,58,237,0.15), rgba(79,70,229,0.1));
            border-top: 1px solid rgba(255,255,255,0.06);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-align: center;
        }
        .cta-title {
            font-size: clamp(1.75rem, 3.5vw, 2.75rem);
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 1rem;
        }
        .cta-desc { color: var(--text-muted); font-size: 1.1rem; margin-bottom: 2.5rem; }
        .btn-cta-white {
            background: #fff;
            color: var(--primary);
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            padding: 0.9rem 2.25rem;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-cta-white:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(255,255,255,0.2);
            color: var(--primary);
        }

        /* ── FOOTER ─────────────────────────────── */
        footer {
            background: rgba(255,255,255,0.03);
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 2rem 0;
            color: var(--text-muted);
            font-size: 0.875rem;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- ══════════════ NAVBAR ══════════════ --}}
    <nav class="navbar-landing">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ url('/') }}" class="navbar-brand-text">
                <i class="bi bi-mortarboard-fill me-2 text-purple" style="color:#a78bfa"></i>GestionStages
            </a>
            <div class="d-flex gap-2 align-items-center">
                <a href="{{ route('login') }}" class="btn nav-btn nav-btn-outline">
                    Connexion
                </a>
                <a href="{{ route('register') }}" class="btn nav-btn nav-btn-primary">
                    Commencer <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </nav>

    {{-- ══════════════ HERO ══════════════ --}}
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="container position-relative">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="hero-badge">
                        <span class="dot-pulse"></span> Plateforme active
                    </div>

                    <h1 class="hero-title">
                        Trouvez votre<br>
                        <span class="gradient-text">stage idéal</span><br>
                        en quelques clics
                    </h1>

                    <p class="hero-desc">
                        GestionStages connecte étudiants et entreprises sur une plateforme
                        professionnelle sécurisée. Publiez des offres, postulez facilement
                        et gérez vos stages de A à Z.
                    </p>

                    <div class="hero-cta-group">
                        <a href="{{ route('register') }}" id="cta-register" class="btn-hero-primary">
                            <i class="bi bi-rocket-takeoff me-2"></i>Commencer gratuitement
                        </a>
                        <a href="{{ route('login') }}" id="cta-login" class="btn-hero-outline">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-number">3</div>
                            <div class="stat-label">Rôles distincts</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">100%</div>
                            <div class="stat-label">Sécurisé</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">∞</div>
                            <div class="stat-label">Offres disponibles</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 d-none d-lg-block">
                    <div class="hero-visual">
                        {{-- Floating Card 1 — Admin --}}
                        <div class="float-card float-card-1">
                            <div class="float-card-icon" style="background:rgba(239,68,68,0.15)">
                                <i class="bi bi-shield-check" style="color:#f87171"></i>
                            </div>
                            <div class="float-card-title">Panneau Administrateur</div>
                            <div class="float-card-sub">Gestion complète des utilisateurs</div>
                            <div class="mt-2 d-flex gap-2">
                                <span class="badge" style="background:rgba(239,68,68,0.2);color:#f87171;font-size:0.7rem;">5 admins</span>
                                <span class="badge" style="background:rgba(16,185,129,0.2);color:#6ee7b7;font-size:0.7rem;">Actif</span>
                            </div>
                        </div>

                        {{-- Floating Card 2 — Offre --}}
                        <div class="float-card float-card-2">
                            <div class="float-card-icon" style="background:rgba(245,158,11,0.15)">
                                <i class="bi bi-briefcase-fill" style="color:#fbbf24"></i>
                            </div>
                            <div class="float-card-title">Nouvelle offre publiée</div>
                            <div class="float-card-sub">Développeur Web — Paris</div>
                            <div class="mt-2">
                                <span class="badge" style="background:rgba(245,158,11,0.2);color:#fbbf24;font-size:0.7rem;">Stage 6 mois</span>
                            </div>
                        </div>

                        {{-- Floating Card 3 — Candidature --}}
                        <div class="float-card float-card-3">
                            <div class="float-card-icon" style="background:rgba(124,58,237,0.15)">
                                <i class="bi bi-check-circle-fill" style="color:#a78bfa"></i>
                            </div>
                            <div class="float-card-title">Candidature acceptée !</div>
                            <div class="float-card-sub">Félicitations, Mohamed</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════ FEATURES ══════════════ --}}
    <section class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label">Fonctionnalités</div>
                <h2 class="section-title">Tout ce dont vous avez besoin</h2>
                <p class="section-desc mx-auto mt-3">
                    Une plateforme complète qui centralise la gestion des stages
                    pour toutes les parties prenantes.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background:rgba(124,58,237,0.15)">
                            <i class="bi bi-person-check-fill" style="color:#a78bfa"></i>
                        </div>
                        <div class="feature-title">Authentification sécurisée</div>
                        <p class="feature-desc">Système de connexion robuste avec contrôle d'accès basé sur les rôles. Chaque utilisateur accède uniquement à ses fonctionnalités.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background:rgba(245,158,11,0.15)">
                            <i class="bi bi-briefcase-fill" style="color:#fbbf24"></i>
                        </div>
                        <div class="feature-title">Gestion des offres</div>
                        <p class="feature-desc">Les entreprises publient et gèrent leurs offres de stage. Les étudiants découvrent et filtrent les opportunités en temps réel.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background:rgba(16,185,129,0.15)">
                            <i class="bi bi-file-earmark-check-fill" style="color:#6ee7b7"></i>
                        </div>
                        <div class="feature-title">Suivi des candidatures</div>
                        <p class="feature-desc">Tableau de bord temps réel pour suivre l'état de chaque candidature : en attente, acceptée ou refusée.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background:rgba(239,68,68,0.15)">
                            <i class="bi bi-speedometer2" style="color:#f87171"></i>
                        </div>
                        <div class="feature-title">Dashboard admin puissant</div>
                        <p class="feature-desc">Statistiques complètes, gestion des utilisateurs CRUD, visualisation graphique des candidatures par statut.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background:rgba(59,130,246,0.15)">
                            <i class="bi bi-shield-lock-fill" style="color:#93c5fd"></i>
                        </div>
                        <div class="feature-title">Sécurité RBAC</div>
                        <p class="feature-desc">Middleware de rôles Laravel strict. Chaque route est protégée — les accès non autorisés sont automatiquement bloqués.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon" style="background:rgba(236,72,153,0.15)">
                            <i class="bi bi-graph-up-arrow" style="color:#f9a8d4"></i>
                        </div>
                        <div class="feature-title">Statistiques & Analytics</div>
                        <p class="feature-desc">Graphiques Chart.js intégrés, métriques par rôle, taux de candidatures et performance des offres publiées.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════ RÔLES ══════════════ --}}
    <section class="roles-section">
        <div class="container">
            <div class="text-center mb-5">
                <div class="section-label">3 Rôles</div>
                <h2 class="section-title">Conçu pour tous</h2>
                <p class="section-desc mx-auto mt-3">
                    Chaque profil dispose d'un espace dédié, adapté à ses besoins spécifiques.
                </p>
            </div>

            <div class="row g-4">
                {{-- Admin --}}
                <div class="col-md-4">
                    <div class="role-card" style="border-color: rgba(239,68,68,0.2);">
                        <div class="role-avatar" style="background:rgba(239,68,68,0.1);">
                            <i class="bi bi-shield-fill-check" style="color:#f87171"></i>
                        </div>
                        <div class="role-title">Administrateur</div>
                        <p class="role-desc">Contrôle total de la plateforme. Supervisez les utilisateurs, les offres et toutes les candidatures.</p>
                        <ul class="role-features">
                            <li><i class="bi bi-check-circle-fill" style="color:#f87171"></i>Gestion des utilisateurs (CRUD)</li>
                            <li><i class="bi bi-check-circle-fill" style="color:#f87171"></i>Vue globale des offres</li>
                            <li><i class="bi bi-check-circle-fill" style="color:#f87171"></i>Statistiques complètes</li>
                            <li><i class="bi bi-check-circle-fill" style="color:#f87171"></i>Contrôle d'accès complet</li>
                        </ul>
                    </div>
                </div>

                {{-- Étudiant --}}
                <div class="col-md-4">
                    <div class="role-card" style="border-color: rgba(124,58,237,0.2);">
                        <div class="role-avatar" style="background:rgba(124,58,237,0.1);">
                            <i class="bi bi-mortarboard-fill" style="color:#a78bfa"></i>
                        </div>
                        <div class="role-title">Étudiant</div>
                        <p class="role-desc">Parcourez les offres de stage, postulez en un clic et suivez vos candidatures en temps réel.</p>
                        <ul class="role-features">
                            <li><i class="bi bi-check-circle-fill" style="color:#a78bfa"></i>Catalogue des offres</li>
                            <li><i class="bi bi-check-circle-fill" style="color:#a78bfa"></i>Candidature simplifiée</li>
                            <li><i class="bi bi-check-circle-fill" style="color:#a78bfa"></i>Suivi des statuts</li>
                            <li><i class="bi bi-check-circle-fill" style="color:#a78bfa"></i>Tableau de bord personnel</li>
                        </ul>
                    </div>
                </div>

                {{-- Entreprise --}}
                <div class="col-md-4">
                    <div class="role-card" style="border-color: rgba(245,158,11,0.2);">
                        <div class="role-avatar" style="background:rgba(245,158,11,0.1);">
                            <i class="bi bi-building-fill" style="color:#fbbf24"></i>
                        </div>
                        <div class="role-title">Entreprise</div>
                        <p class="role-desc">Publiez vos offres de stage, gérez les candidatures reçues et trouvez les meilleurs talents.</p>
                        <ul class="role-features">
                            <li><i class="bi bi-check-circle-fill" style="color:#fbbf24"></i>Publication d'offres</li>
                            <li><i class="bi bi-check-circle-fill" style="color:#fbbf24"></i>Gestion des offres</li>
                            <li><i class="bi bi-check-circle-fill" style="color:#fbbf24"></i>Traitement des candidatures</li>
                            <li><i class="bi bi-check-circle-fill" style="color:#fbbf24"></i>Statistiques entreprise</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ══════════════ CTA ══════════════ --}}
    <section class="cta-section">
        <div class="container">
            <h2 class="cta-title">
                Prêt à commencer votre<br>
                <span class="gradient-text">aventure professionnelle ?</span>
            </h2>
            <p class="cta-desc">
                Rejoignez la plateforme et accédez à votre espace personnalisé.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('register') }}" class="btn-cta-white">
                    <i class="bi bi-person-plus-fill"></i> Créer un compte
                </a>
                <a href="{{ route('login') }}" class="btn-hero-outline" style="padding:0.9rem 2rem; font-size: 1rem; text-decoration: none;">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </a>
            </div>
        </div>
    </section>

    {{-- ══════════════ FOOTER ══════════════ --}}
    <footer>
        <div class="container">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <span>
                    <i class="bi bi-mortarboard-fill me-1" style="color:#a78bfa"></i>
                    <strong style="color:#fff;">GestionStages</strong>
                </span>
                <span>&copy; {{ date('Y') }} — Plateforme de gestion des stages. Tous droits réservés.</span>
                <div class="d-flex gap-3">
                    <a href="{{ route('login') }}" style="color: rgba(255,255,255,0.5); text-decoration:none; font-size:0.85rem;">Connexion</a>
                    <a href="{{ route('register') }}" style="color: rgba(255,255,255,0.5); text-decoration:none; font-size:0.85rem;">Inscription</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
