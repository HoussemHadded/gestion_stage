<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Erreur serveur · StageHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', theme: { extend: { fontFamily: { sans: ['Inter','system-ui','sans-serif'] } } } }</script>
    <script>if(localStorage.getItem('darkMode')==='true')document.documentElement.classList.add('dark');</script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .error-gradient { background: linear-gradient(135deg, #1a0a0a 0%, #2d0f0f 30%, #1a0a15 60%, #0f0a1a 100%); }
        .orb { position:absolute; border-radius:50%; filter:blur(70px); opacity:0.35; }
        @keyframes float { 0%,100%{transform:translate(0,0);} 50%{transform:translate(15px,-25px);} }
        .orb-1 { width:300px;height:300px;background:rgba(239,68,68,0.3);top:10%;left:5%;animation:float 20s ease-in-out infinite; }
        .orb-2 { width:250px;height:250px;background:rgba(245,101,101,0.2);bottom:15%;right:10%;animation:float 25s ease-in-out infinite reverse; }
        @keyframes pulse500 { 0%,100%{opacity:1;} 50%{opacity:0.6;} }
        .pulse-animate { animation: pulse500 2s ease-in-out infinite; }
        @keyframes fadeUp { from{opacity:0;transform:translateY(20px);} to{opacity:1;transform:translateY(0);} }
        .fade-up { animation: fadeUp 0.7s ease-out forwards; }
        .fade-up-delay { animation: fadeUp 0.7s ease-out 0.2s forwards; opacity:0; }
    </style>
</head>
<body class="h-full font-sans antialiased error-gradient min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,0.02) 1px,transparent 1px);background-size:32px 32px;"></div>

    <div class="text-center max-w-lg relative z-10">

        {{-- 500 Number --}}
        <div class="pulse-animate mb-6 fade-up">
            <span class="text-[120px] sm:text-[160px] font-black leading-none select-none"
                  style="background:linear-gradient(135deg,#f87171,#ef4444,#dc2626);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                500
            </span>
        </div>

        {{-- Icon --}}
        <div class="w-20 h-20 bg-red-500/10 border border-red-500/20 rounded-3xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm fade-up">
            <i data-lucide="circle" class="text-4xl text-red-400 inline-block"></i>
        </div>

        {{-- Message --}}
        <div class="fade-up-delay">
            <h1 class="text-2xl sm:text-3xl font-black text-white mb-3 tracking-tight">Erreur interne du serveur</h1>
            <p class="text-slate-400 text-base leading-relaxed mb-8">
                Quelque chose s'est mal passé de notre côté.<br>
                Notre équipe a été notifiée. Veuillez réessayer dans quelques instants.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}"
                   class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-red-500/30 hover:shadow-red-500/50 hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
                    <i data-lucide="home" class=" inline-block"></i>Retour à l'accueil
                </a>
                <button onclick="window.location.reload()"
                        class="w-full sm:w-auto px-8 py-3.5 bg-luxury-surface/5 hover:bg-luxury-surface/10 border border-white/10 text-slate-300 hover:text-white text-sm font-bold rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                    <i data-lucide="circle" class=" inline-block"></i>Réessayer
                </button>
            </div>
        </div>

        {{-- Brand --}}
        <div class="mt-12 flex items-center justify-center gap-2 opacity-40">
            <div class="w-6 h-6 bg-gradient-to-br from-red-500 to-rose-600 rounded-lg flex items-center justify-center">
                <i data-lucide="graduation-cap" class="text-white text-[10px] inline-block"></i>
            </div>
            <span class="text-luxury-muted text-xs font-bold uppercase tracking-widest">StageHub</span>
        </div>
    </div>
</body>
</html>
