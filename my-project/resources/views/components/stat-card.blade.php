@props(['title', 'value', 'icon' => null, 'trend' => null, 'iconClass' => 'bg-primary/20 text-warning'])

<x-bento-card class="p-6 justify-between h-full">
    <div class="flex justify-between items-start mb-4">
        <h3 class="text-xs font-bold text-secondary uppercase tracking-widest">{{ $title }}</h3>
        @if($icon)
            <div class="p-2.5 rounded-xl flex items-center justify-center {{ $iconClass }}">
                <i class="bi {{ $icon }} text-lg"></i>
            </div>
        @endif
    </div>
    <div>
        <div class="text-3xl font-extrabold text-text tracking-tight">{{ $value }}</div>
        @if($trend)
            <p class="text-sm font-medium mt-2 text-secondary">{!! $trend !!}</p>
        @endif
    </div>
</x-bento-card>
