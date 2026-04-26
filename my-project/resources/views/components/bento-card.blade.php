<div {{ $attributes->merge(['class' => 'bg-surface rounded-2xl shadow-sm border border-black/5 hover:-translate-y-1 hover:shadow-md transition-all duration-300 overflow-hidden flex flex-col']) }}>
    {{ $slot }}
</div>
