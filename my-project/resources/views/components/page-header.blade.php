@props([
    'title'    => '',
    'subtitle' => null,
    'icon'     => null,
    'iconColor' => 'from-gold-primary to-gold-soft',
])
<div {{ $attributes->merge(['class' => 'mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4']) }}>
    <div>
        <h2 class="text-3xl font-black text-white text-white tracking-tight flex items-center gap-3">
            @if($icon)
            <div class="w-10 h-10 bg-gradient-to-br {{ $iconColor }} rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/20 flex-shrink-0">
                <i class="bi {{ $icon }} text-white text-sm"></i>
            </div>
            @endif
            {{ $title }}
        </h2>
        @if($subtitle)
            <p class="mt-2 text-sm text-luxury-muted dark:text-slate-400 font-medium">{{ $subtitle }}</p>
        @endif
    </div>
    @if(isset($actions))
    <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
        {{ $actions }}
    </div>
    @endif
</div>
