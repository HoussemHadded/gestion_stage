@props([
    'icon'     => 'bi-inbox',
    'title'    => 'Aucun résultat',
    'message'  => 'Aucune donnée disponible pour le moment.',
    'actionLabel' => null,
    'actionUrl'   => null,
    'actionIcon'  => 'bi-plus-circle',
])
<div {{ $attributes->merge(['class' => 'py-16 text-center']) }}>
    <div class="w-20 h-20 bg-luxury-surface2 dark:bg-luxury-surface2/60 rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-inner">
        <i class="bi {{ $icon }} text-3xl text-slate-400 dark:text-luxury-muted"></i>
    </div>
    <h3 class="text-lg font-bold text-white text-white mb-2">{{ $title }}</h3>
    <p class="text-sm text-luxury-muted dark:text-slate-400 max-w-sm mx-auto leading-relaxed">{{ $message }}</p>
    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-gradient-to-r from-gold-primary to-gold-soft hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-gold-primary/25 hover:shadow-gold-primary/40 transition-all duration-300 hover:scale-[1.02]">
            <i class="bi {{ $actionIcon }}"></i>{{ $actionLabel }}
        </a>
    @endif
    @if(isset($slot) && $slot->isNotEmpty())
        <div class="mt-6">{{ $slot }}</div>
    @endif
</div>
