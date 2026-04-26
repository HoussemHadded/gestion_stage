@props([
    'hover'   => false,
    'padding' => 'p-6',
    'radius'  => 'rounded-2xl',
    'class'   => '',
])

@php
    $hoverCls = $hover
        ? 'transition-all duration-300 cursor-pointer hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/50 dark:hover:shadow-black/30'
        : '';
@endphp

<div {{ $attributes->merge(['class' => "glass-card $radius $padding $hoverCls $class"]) }}>
    {{ $slot }}
</div>
