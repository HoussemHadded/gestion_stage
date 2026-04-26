@props([
    'label'   => 'Metric',
    'value'   => '0',
    'icon'    => 'bi-bar-chart',
    'color'   => 'indigo',  // indigo | emerald | amber | red | blue | purple
    'sub'     => null,
    'gradient' => false,    // if true renders as colored gradient card
    'bar'     => null,      // 0-100 percentage for progress bar
])

@php
    $colorMap = [
        'indigo' => [
            'icon_bg'  => 'bg-gradient-to-br from-gold-primary/10 to-gold-primary/5',
            'icon_txt' => 'text-gold-primary',
            'icon_bdr' => 'border-gold-primary/20',
            'blob'     => 'bg-gold-primary/10',
            'bar'      => 'bg-gold-primary',
        ],
        'emerald' => [
            'icon_bg'  => 'bg-gradient-to-br from-green-500/10 to-green-500/5',
            'icon_txt' => 'text-green-500',
            'icon_bdr' => 'border-green-500/20',
            'blob'     => 'bg-green-500/10',
            'bar'      => 'bg-green-500',
        ],
        'amber' => [
            'icon_bg'  => 'bg-gradient-to-br from-yellow-500/10 to-yellow-500/5',
            'icon_txt' => 'text-yellow-500',
            'icon_bdr' => 'border-yellow-500/20',
            'blob'     => 'bg-yellow-500/10',
            'bar'      => 'bg-yellow-500',
        ],
        'red' => [
            'icon_bg'  => 'bg-gradient-to-br from-red-500/10 to-red-500/5',
            'icon_txt' => 'text-red-500',
            'icon_bdr' => 'border-red-500/20',
            'blob'     => 'bg-red-500/10',
            'bar'      => 'bg-red-500',
        ],
        'blue' => [
            'icon_bg'  => 'bg-gradient-to-br from-[#3B82F6]/10 to-[#3B82F6]/5',
            'icon_txt' => 'text-[#3B82F6]',
            'icon_bdr' => 'border-[#3B82F6]/20',
            'blob'     => 'bg-[#3B82F6]/10',
            'bar'      => 'bg-[#3B82F6]',
        ],
        'purple' => [
            'icon_bg'  => 'bg-gradient-to-br from-gold-soft/10 to-gold-soft/5',
            'icon_txt' => 'text-gold-soft',
            'icon_bdr' => 'border-gold-soft/20',
            'blob'     => 'bg-gold-soft/10',
            'bar'      => 'bg-gold-soft',
        ],
    ];
    $c = $colorMap[$color] ?? $colorMap['indigo'];
@endphp

@if($gradient)
    {{-- Gradient colored card variant --}}
    @php
        $gradients = [
            'emerald' => 'bg-gradient-to-br from-emerald-500 to-teal-500 shadow-emerald-500/20',
            'amber'   => 'bg-gradient-to-br from-amber-400 to-orange-400 shadow-amber-500/20',
            'indigo'  => 'bg-gradient-to-br from-gold-primary to-gold-soft text-black shadow-indigo-500/20',
            'red'     => 'bg-gradient-to-br from-red-500 to-rose-500 shadow-red-500/20',
            'blue'    => 'bg-gradient-to-br from-blue-500 to-indigo-500 shadow-blue-500/20',
            'purple'  => 'bg-gradient-to-br from-purple-500 to-indigo-600 shadow-purple-500/20',
        ];
        $grad = $gradients[$color] ?? $gradients['indigo'];
    @endphp
    <div {{ $attributes->merge(['class' => "$grad rounded-2xl shadow-lg p-6 flex flex-col text-white premium-hover relative overflow-hidden"]) }}>
        <div class="absolute -right-4 -top-4 w-20 h-20 bg-luxury-surface/20 rounded-full"></div>
        <p class="text-[10px] font-extrabold text-white/70 uppercase tracking-widest relative z-10 flex items-center gap-1.5">
            <i class="bi {{ $icon }}"></i>{{ $label }}
        </p>
        <h3 class="text-4xl font-extrabold mt-3 relative z-10">{{ $value }}</h3>
        @if($sub)
            <p class="text-xs text-white/70 font-medium relative z-10 mt-3">{{ $sub }}</p>
        @endif
        @if($bar !== null)
            <div class="w-full bg-luxury-surface/30 rounded-full h-1 mt-4 relative z-10">
                <div class="bg-luxury-surface h-1 rounded-full transition-all duration-1000" style="width: {{ min(100, max(0, $bar)) }}%"></div>
            </div>
        @endif
    </div>
@else
    {{-- Glass card variant --}}
    <div {{ $attributes->merge(['class' => "glass-card rounded-2xl p-6 flex flex-col premium-hover relative overflow-hidden group"]) }}>
        <div class="absolute -right-4 -top-4 w-24 h-24 {{ $c['blob'] }} rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
        <div class="w-12 h-12 {{ $c['icon_bg'] }} {{ $c['icon_txt'] }} rounded-xl flex items-center justify-center text-xl mb-4 relative z-10 shadow-sm border {{ $c['icon_bdr'] }}">
            <i class="bi {{ $icon }}"></i>
        </div>
        <p class="text-xs font-bold text-luxury-muted uppercase tracking-widest relative z-10">{{ $label }}</p>
        <h3 class="text-4xl font-extrabold text-white mt-2 relative z-10">{{ $value }}</h3>
        @if($sub)
            <p class="text-xs text-gray-400 font-medium relative z-10 mt-3 border-t border-luxury-borderSoft pt-3">{{ $sub }}</p>
        @endif
        @if($bar !== null)
            <div class="w-full bg-[#2A2A2A] rounded-full h-1 mt-4 relative z-10">
                <div class="{{ $c['bar'] }} h-1 rounded-full transition-all duration-1000" style="width: {{ min(100, max(0, $bar)) }}%"></div>
            </div>
        @endif
    </div>
@endif
