@props([
    'variant' => 'default',  // success | warning | danger | info | default | purple | indigo
    'size'    => 'md',       // sm | md | lg
    'dot'     => false,
    'pill'    => true,
])

@php
    $variants = [
        'success' => 'bg-emerald-100 dark:bg-emerald-500/15 text-emerald-800 dark:text-emerald-400 border-emerald-200/80 dark:border-emerald-500/20',
        'warning' => 'bg-amber-100 dark:bg-amber-500/15 text-amber-800 dark:text-amber-400 border-amber-200/80 dark:border-amber-500/20',
        'danger'  => 'bg-red-100 dark:bg-red-500/15 text-red-800 dark:text-red-400 border-red-200/80 dark:border-red-500/20',
        'info'    => 'bg-blue-100 dark:bg-blue-500/15 text-blue-800 dark:text-blue-400 border-blue-200/80 dark:border-blue-500/20',
        'indigo'  => 'bg-gold-primary/20 text-gold-primary dark:bg-gold-primary text-black/15 text-indigo-800 dark:text-gold-primary border-indigo-200/80 dark:border-gold-primary/50/20',
        'purple'  => 'bg-gold-soft/20 text-gold-soft dark:bg-purple-500/15 text-purple-800 dark:text-purple-400 border-purple-200/80 dark:border-purple-500/20',
        'default' => 'bg-luxury-surface2 dark:bg-slate-700/50 text-gray-300 dark:text-slate-300 border-luxury-borderSoft/80 dark:border-slate-600/50',
    ];
    $sizes = [
        'sm' => 'px-2 py-0.5 text-[9px]',
        'md' => 'px-2.5 py-1 text-[10px]',
        'lg' => 'px-3 py-1.5 text-xs',
    ];
    $dotColors = [
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger'  => 'bg-red-500',
        'info'    => 'bg-blue-500',
        'indigo'  => 'bg-gold-primary text-black',
        'purple'  => 'bg-purple-500',
        'default' => 'bg-slate-400',
    ];
    $cls = $variants[$variant] ?? $variants['default'];
    $sz  = $sizes[$size] ?? $sizes['md'];
    $dc  = $dotColors[$variant] ?? $dotColors['default'];
    $radius = $pill ? 'rounded-full' : 'rounded-lg';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 $sz $cls $radius border font-extrabold uppercase tracking-wide"]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dc }} flex-shrink-0"></span>
    @endif
    {{ $slot }}
</span>
