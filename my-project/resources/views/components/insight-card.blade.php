@props(['title', 'icon' => 'bi-lightbulb', 'items' => [], 'score' => null, 'scoreLabel' => 'Score', 'accentClass' => 'from-secondary/20 to-primary/20'])

<x-bento-card class="p-5">
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-4 pb-3 border-b border-black/5">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $accentClass }} flex items-center justify-center shrink-0">
            <i class="bi {{ $icon }} text-secondary text-sm"></i>
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-text text-sm">{{ $title }}</h4>
        </div>
        @if($score !== null)
        <div class="shrink-0 text-right">
            <div class="text-xl font-extrabold text-text">{{ $score }}<span class="text-xs font-medium text-secondary">/100</span></div>
            <div class="text-[10px] font-bold uppercase text-secondary tracking-wider">{{ $scoreLabel }}</div>
        </div>
        @endif
    </div>

    {{-- Score Bar --}}
    @if($score !== null)
    <div class="w-full bg-black/5 rounded-full h-2 mb-4">
        <div class="h-2 rounded-full transition-all duration-700
            {{ $score >= 75 ? 'bg-success' : ($score >= 50 ? 'bg-warning' : 'bg-danger') }}"
            style="width: {{ min($score, 100) }}%">
        </div>
    </div>
    @endif

    {{-- Bullet Items --}}
    @if(count($items))
    <ul class="space-y-2">
        @foreach($items as $item)
        <li class="flex items-start gap-2 text-xs text-text/80 leading-snug">
            <i class="bi bi-check2-circle text-success mt-0.5 shrink-0"></i>
            <span>{{ $item }}</span>
        </li>
        @endforeach
    </ul>
    @endif

    {{-- Slot for extra content --}}
    @if(isset($slot) && $slot->isNotEmpty())
    <div class="mt-4">{{ $slot }}</div>
    @endif
</x-bento-card>
