@extends('layouts.app')

@section('title', 'Pipeline Kanban')

@section('content')

{{-- CDN SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
    <div>
        <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-600 flex items-center">
            <i class="bi bi-kanban text-amber-500 mr-3"></i>Pipeline Kanban
        </h2>
        <p class="mt-2 text-sm text-gray-500 font-medium">Glissez-déposez les candidats pour mettre à jour leur statut.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('entreprise.candidatures.index') }}" class="px-5 py-2.5 glass-card text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-xl shadow-sm transition flex items-center premium-hover">
            <i class="bi bi-list-ul text-indigo-500 mr-2"></i>Vue Liste
        </a>
        <a href="{{ route('entreprise.dashboard') }}" class="px-5 py-2.5 glass-card text-gray-700 hover:bg-gray-50 text-sm font-bold rounded-xl shadow-sm transition flex items-center premium-hover">
            <i class="bi bi-grid-1x2-fill text-indigo-500 mr-2"></i>Dashboard
        </a>
    </div>
</div>

<div x-data="kanbanBoard()" x-init="initKanban()" class="flex flex-col md:flex-row overflow-x-auto pb-8 gap-6 w-full -mx-4 px-4 sm:mx-0 sm:px-0">
    
    @php
        $columns = [
            'en_attente' => ['label' => 'En Attente', 'icon' => 'bi-inbox', 'color' => 'amber'],
            'shortlisted' => ['label' => 'Présélection', 'icon' => 'bi-star', 'color' => 'blue'],
            'interview' => ['label' => 'Entretien', 'icon' => 'bi-calendar-check', 'color' => 'indigo'],
            'accepte' => ['label' => 'Embauché', 'icon' => 'bi-check-circle', 'color' => 'emerald'],
            'refuse' => ['label' => 'Refusé', 'icon' => 'bi-x-circle', 'color' => 'red']
        ];
    @endphp

    @foreach($columns as $statusKey => $columnMeta)
    <div class="flex flex-col flex-shrink-0 w-full md:w-80">
        <div class="flex items-center justify-between mb-4 px-1">
            <h3 class="text-sm font-extrabold text-gray-700 uppercase tracking-widest flex items-center">
                <i class="bi {{ $columnMeta['icon'] }} text-{{ $columnMeta['color'] }}-500 mr-2 text-lg"></i>
                {{ $columnMeta['label'] }}
            </h3>
            <span class="bg-{{ $columnMeta['color'] }}-100 text-{{ $columnMeta['color'] }}-700 font-black text-xs px-2.5 py-1 rounded-full shadow-sm">
                {{ count($pipeline[$statusKey]) }}
            </span>
        </div>
        
        <div class="bg-gray-50/80 rounded-3xl p-4 border border-gray-100/80 shadow-inner flex flex-col flex-grow min-h-[500px]" 
             id="column-{{ $statusKey }}" 
             data-status="{{ $statusKey }}">
            
            @foreach($pipeline[$statusKey] as $candidature)
                <div class="glass-card rounded-2xl p-5 mb-4 shadow-sm border border-gray-100/80 cursor-grab active:cursor-grabbing hover:border-indigo-300 transition duration-150 relative group"
                     data-id="{{ $candidature->id }}">
                    
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-tr from-gray-100 to-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600 text-sm shadow-sm border border-white">
                                {{ mb_substr($candidature->student->name ?? '?', 0, 1) }}
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ $candidature->student->name ?? 'Anonyme' }}</p>
                                <p class="text-[10px] text-gray-400 font-extrabold tracking-wide uppercase">{{ $candidature->date_candidature ? $candidature->date_candidature->format('d M y') : '' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-xl p-3 mb-4 border border-gray-100">
                        <p class="text-xs font-bold text-gray-700 leading-tight">{{ $candidature->offre->titre }}</p>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100/50 pt-3">
                        <span class="text-[10px] text-gray-400 font-bold flex items-center">
                            <i class="bi bi-grip-horizontal mr-1 text-gray-300"></i> Dépacer
                        </span>
                        
                        @if(isset($candidature->match_score))
                            @php
                                $score = $candidature->match_score;
                                $scoreColor = $score >= 80 ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : ($score >= 50 ? 'text-amber-600 bg-amber-50 border-amber-100' : 'text-red-600 bg-red-50 border-red-100');
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-black border shadow-sm {{ $scoreColor }}">
                                <i class="bi bi-robot mr-1"></i>{{ round($score) }}%
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endforeach

</div>

{{-- Notification Toast --}}
<div id="toast" class="fixed bottom-5 right-5 transform translate-y-20 opacity-0 transition-all duration-300 bg-gray-900 text-white px-6 py-3 rounded-xl shadow-2xl flex items-center font-medium z-50">
    <i id="toast-icon" class="bi mr-3 text-lg"></i>
    <span id="toast-msg">Mise à jour...</span>
</div>

@push('scripts')
<script>
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const msg = document.getElementById('toast-msg');
        const icon = document.getElementById('toast-icon');
        
        msg.textContent = message;
        icon.className = type === 'success' ? 'bi bi-check-circle-fill text-emerald-400 mr-3 text-lg' : 'bi bi-exclamation-triangle-fill text-red-400 mr-3 text-lg';
        
        toast.classList.remove('translate-y-20', 'opacity-0');
        setTimeout(() => toast.classList.add('translate-y-20', 'opacity-0'), 3000);
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('kanbanBoard', () => ({
            initKanban() {
                const columns = document.querySelectorAll('[id^="column-"]');
                
                columns.forEach(col => {
                    new Sortable(col, {
                        group: 'kanban',
                        animation: 150,
                        ghostClass: 'opacity-50',
                        dragClass: 'scale-105',
                        easing: "cubic-bezier(1, 0, 0, 1)",
                        delay: 100, // Mobile touch delay prevents scrolling interception
                        delayOnTouchOnly: true, // Only delay on mobile
                        onEnd: (evt) => {
                            if (evt.to === evt.from) return; // No column change

                            const candidatureId = evt.item.dataset.id;
                            const newStatus = evt.to.dataset.status;

                            // Send AJAX update
                            fetch(`/entreprise/candidatures/${candidatureId}/status`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ status: newStatus })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if(data.success) {
                                    showToast('Statut mis à jour avec succès.', 'success');
                                    // Update visual counts
                                    updateCounts();
                                } else {
                                    showToast('Erreur lors de la mise à jour.', 'error');
                                    // Revert visual drag if error
                                    evt.from.appendChild(evt.item);
                                }
                            })
                            .catch(err => {
                                showToast('Erreur de connexion.', 'error');
                                evt.from.appendChild(evt.item);
                            });
                        }
                    });
                });
            }
        }));
    });

    function updateCounts() {
        // Simple DOM update for counts after drop
        ['en_attente', 'shortlisted', 'interview', 'accepte', 'refuse'].forEach(status => {
            const col = document.getElementById(`column-${status}`);
            if(col) {
                const count = col.querySelectorAll('.glass-card').length;
                const badge = col.previousElementSibling.querySelector('span');
                if(badge) badge.textContent = count;
            }
        });
    }
</script>
@endpush

@endsection
