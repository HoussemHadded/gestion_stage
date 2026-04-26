<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page introuvable · StageHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class', theme: { extend: { fontFamily: { sans: ['Inter','system-ui','sans-serif'] } } } }</script>
    <script>if(localStorage.getItem('darkMode')==='true')document.documentElement.classList.add('dark');</script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .error-gradient {
            background: linear-gradient(135deg, #0f0a2e 0%, #1a1145 30%, #0c1445 60%, #0a0e2e 100%);
        }
        .orb { position:absolute; border-radius:50%; filter:blur(60px); opacity:0.4; }
        @keyframes float {
            0%,100% { transform:translate(0,0) scale(1); }
            50% { transform:translate(20px,-30px) scale(1.05); }
        }
        .orb-1 { width:350px;height:350px;background:rgba(99,102,241,0.3);top:5%;left:10%;animation:float 18s ease-in-out infinite; }
        .orb-2 { width:280px;height:280px;background:rgba(139,92,246,0.25);bottom:10%;right:15%;animation:float 22s ease-in-out infinite reverse; }
        @keyframes bounce404 {
            0%,100% { transform:translateY(0); }
            50% { transform:translateY(-12px); }
        }
        .number-animate { animation: bounce404 3s ease-in-out infinite; }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(20px); }
            to { opacity:1; transform:translateY(0); }
        }
        .fade-up { animation: fadeUp 0.7s ease-out forwards; }
        .fade-up-delay { animation: fadeUp 0.7s ease-out 0.2s forwards; opacity:0; }
    </style>
</head>
<body class="h-full font-sans antialiased error-gradient min-h-screen flex items-center justify-center p-6 relative overflow-hidden">

    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="absolute inset-0 pointer-events-none" style="background-image:radial-gradient(rgba(255,255,255,0.03) 1px,transparent 1px);background-size:32px 32px;"></div>

    <div class="text-center max-w-lg relative z-10">

        {{-- 404 Number --}}
        <div class="number-animate mb-6 fade-up">
            <span class="text-[120px] sm:text-[160px] font-black leading-none select-none"
                  style="background:linear-gradient(135deg,#818cf8,#a78bfa,#c084fc);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                404
            </span>
        </div>

        {{-- Icon --}}
        <div class="w-20 h-20 bg-luxury-surface/5 border border-white/10 rounded-3xl flex items-center justify-center mx-auto mb-6 backdrop-blur-sm fade-up">
            <i data-lucide="circle" class="text-4xl text-gold-primary inline-block"></i>
        </div>

        {{-- Message --}}
        <div class="fade-up-delay">
            <h1 class="text-2xl sm:text-3xl font-black text-white mb-3 tracking-tight">Page introuvable</h1>
            <p class="text-slate-400 text-base leading-relaxed mb-8">
                La page que vous recherchez n'existe pas ou a été déplacée.<br>
                Vérifiez l'URL ou retournez à l'accueil.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}"
                   class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-gold-primary to-gold-soft text-black hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] transition-all duration-300 flex items-center justify-center gap-2">
                    <i data-lucide="home" class=" inline-block"></i>Retour à l'accueil
                </a>
                <button onclick="history.back()"
                        class="w-full sm:w-auto px-8 py-3.5 bg-luxury-surface/5 hover:bg-luxury-surface/10 border border-white/10 text-slate-300 hover:text-white text-sm font-bold rounded-xl transition-all duration-300 flex items-center justify-center gap-2">
                    <i data-lucide="arrow-left" class=" inline-block"></i>Page précédente
                </button>
            </div>
        </div>

        {{-- Brand --}}
        <div class="mt-12 flex items-center justify-center gap-2 opacity-50">
            <div class="w-6 h-6 bg-gradient-to-br from-gold-primary to-gold-soft text-black rounded-lg flex items-center justify-center">
                <i data-lucide="graduation-cap" class="text-white text-[10px] inline-block"></i>
            </div>
            <span class="text-luxury-muted text-xs font-bold uppercase tracking-widest">StageHub</span>
        </div>
    </div>
</body>
</html>
