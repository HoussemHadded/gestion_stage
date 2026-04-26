@extends('layouts.app')

@section('title', 'Mes Notifications')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Toutes mes notifications</h1>
        @if(auth()->user()->unreadNotifications->count() > 0)
        <button id="mark-all-read-page" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-4 py-2 rounded-md text-sm font-medium transition">
            <i class="bi bi-check2-all mr-1"></i> Tout marquer comme lu
        </button>
        @endif
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <ul class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <li class="p-4 hover:bg-gray-50 transition {{ empty($notification->read_at) ? 'bg-indigo-50/50' : '' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            @php $typeLabel = $notification->data['type_label'] ?? ''; @endphp

                            @if($typeLabel === 'nouvelle_offre')
                                <i class="bi bi-briefcase-fill text-indigo-500 text-xl"></i>
                            @elseif($typeLabel === 'nouvelle_candidature')
                                <i class="bi bi-file-earmark-person-fill text-blue-500 text-xl"></i>
                            @elseif($typeLabel === 'candidature_acceptee')
                                <i class="bi bi-check-circle-fill text-green-500 text-xl"></i>
                            @elseif($typeLabel === 'candidature_refusee')
                                <i class="bi bi-x-circle-fill text-red-500 text-xl"></i>
                            @else
                                <i class="bi bi-bell-fill text-gray-400 text-xl"></i>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                </p>
                                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            @if(!empty($notification->data['offre_titre']))
                            <p class="text-sm text-gray-500 mt-1">Concerne : {{ $notification->data['offre_titre'] }}</p>
                            @endif
                            <div class="mt-2">
                                <a href="{{ $notification->data['url'] ?? '#' }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">Consulter <span aria-hidden="true">&rarr;</span></a>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-8 text-center text-gray-500">
                    <i class="bi bi-bell-slash text-4xl block mb-2 text-gray-300"></i>
                    Vous n'avez aucune notification.
                </li>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mark-all-read-page');
        if(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('{{ route('notifications.markAllRead') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    window.location.reload();
                });
            });
        }
    });
</script>
@endpush
@endsection
