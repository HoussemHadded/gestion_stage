@props(['name', 'email', 'match' => 0, 'cvScore' => null, 'statut' => null, 'offreTitre' => '', 'candidatureDate' => '', 'manageUrl' => '#'])

@php
    $matchColor = $match >= 75 ? 'success' : ($match >= 50 ? 'warning' : 'danger');
    $matchBg = $match >= 75 ? 'bg-success/10 text-success' : ($match >= 50 ? 'bg-warning/10 text-warning' : 'bg-danger/10 text-danger');
    $barColor = $match >= 75 ? 'bg-success' : ($match >= 50 ? 'bg-warning' : 'bg-danger');
    $initial = strtoupper(substr($name, 0, 1));
@endphp

<x-bento-card class="p-5">
    {{-- Header: Avatar + Name + Match Badge --}}
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center gap-3">
            <div class="h-11 w-11 rounded-full bg-secondary/20 text-secondary font-extrabold text-lg flex items-center justify-center shrink-0">
                {{ $initial }}
            </div>
            <div>
                <p class="font-bold text-text text-sm leading-tight">{{ $name }}</p>
                <p class="text-xs text-secondary truncate max-w-[140px]">{{ $email }}</p>
            </div>
        </div>
        {{-- Match Score Pill --}}
        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $matchBg }} shrink-0">
            {{ $match }}% match
        </span>
    </div>

    {{-- AI Match Progress Bar --}}
    <div class="mb-4">
        <div class="flex justify-between text-[10px] font-semibold uppercase tracking-wider text-secondary mb-1">
            <span>Score IA</span>
            <span>{{ $match }}/100</span>
        </div>
        <div class="w-full bg-black/5 rounded-full h-2">
            <div class="{{ $barColor }} h-2 rounded-full transition-all duration-500" style="width: {{ min($match, 100) }}%"></div>
        </div>
    </div>

    {{-- Offer + CV Score --}}
    <div class="bg-black/[0.03] rounded-xl p-3 mb-4 space-y-2">
        <div class="flex items-start gap-2">
            <i class="bi bi-briefcase text-secondary text-xs mt-0.5"></i>
            <p class="text-xs text-text font-medium line-clamp-1">{{ $offreTitre }}</p>
        </div>
        @if($cvScore)
        <div class="flex items-center gap-2">
            <i class="bi bi-star-fill text-warning text-xs"></i>
            <p class="text-xs text-text font-medium">CV Score: <span class="font-bold text-secondary">{{ $cvScore }}/100</span></p>
        </div>
        @endif
        @if($statut)
        <div class="flex items-center gap-2">
            <i class="bi bi-circle-fill text-[8px] {{ str_contains($statut, 'acceptée') ? 'text-success' : (str_contains($statut, 'attente') ? 'text-warning' : 'text-danger') }}"></i>
            <p class="text-xs font-semibold {{ str_contains($statut, 'acceptée') ? 'text-success' : (str_contains($statut, 'attente') ? 'text-warning' : 'text-danger') }}">{{ $statut }}</p>
        </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="flex items-center justify-between mt-auto pt-3 border-t border-black/5">
        <span class="text-[10px] text-secondary">
            <i class="bi bi-clock mr-1"></i>{{ $candidatureDate }}
        </span>
        <a href="{{ $manageUrl }}" class="text-xs font-bold text-secondary hover:text-text transition-colors flex items-center gap-1">
            Gérer <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</x-bento-card>
