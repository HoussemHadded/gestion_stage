@extends('layouts.app')

@section('title', 'Candidats Intelligents')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Évaluation des Candidats</h1>
            <p class="text-sm text-gray-500 mt-1">Découvrez et triez les candidats ayant postulé à vos offres selon l'analyse IA.</p>
        </div>
        
        <!-- Filters Form -->
        <form method="GET" action="{{ route('entreprise.candidats.index') }}" class="flex space-x-3">
            <select name="offre_id" class="border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 pr-8" onchange="this.form.submit()">
                <option value="">Toutes mes offres</option>
                @foreach($offres as $o)
                    <option value="{{ $o->id }}" {{ request('offre_id') == $o->id ? 'selected' : '' }}>
                        {{ Str::limit($o->titre, 30) }}
                    </option>
                @endforeach
            </select>
            
            <select name="sort" class="border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500 pr-8" onchange="this.form.submit()">
                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>Plus récents</option>
                <option value="match" {{ $sort == 'match' ? 'selected' : '' }}>Meilleur Match IA (%)</option>
                <option value="cv_score" {{ $sort == 'cv_score' ? 'selected' : '' }}>Meilleur Score CV Global</option>
            </select>
        </form>
    </div>

    <!-- Candidates Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($candidates as $candidature)
            @php $student = $candidature->student; @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <div class="h-12 w-12 bg-indigo-100 text-indigo-700 rounded-full flex items-center justify-center font-bold text-xl uppercase">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">{{ $student->name }}</h3>
                                <p class="text-xs text-gray-500 truncate w-32">{{ $student->email }}</p>
                            </div>
                        </div>
                        
                        <!-- AI Match Badge -->
                        @php $match = $candidature->match_percentage ?? 0; @endphp
                        <div class="flex flex-col items-center">
                            <div class="relative flex items-center justify-center h-12 w-12 rounded-full border-4 {{ $match >= 80 ? 'border-green-400 text-green-700 bg-green-50' : ($match >= 50 ? 'border-yellow-400 text-yellow-700 bg-yellow-50' : 'border-gray-200 text-gray-500') }}">
                                <span class="font-bold text-xs">{{ $match }}%</span>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase mt-1">Match Score</span>
                        </div>
                    </div>

                    <div class="mt-4 border-t border-gray-100 pt-4">
                        <h4 class="text-xs uppercase font-bold text-gray-400 tracking-wider">Candidature pour</h4>
                        <p class="text-sm font-medium text-gray-800 mt-1 lines-truncate-2">{{ $candidature->offre->titre }}</p>
                    </div>

                    <div class="mt-4 bg-gray-50 p-3 rounded-lg flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500 font-medium">CV Qualité (IA)</span>
                            <span class="font-bold text-sm text-indigo-700">
                                {{ $student->cv_score ?? '-' }} / 100
                            </span>
                        </div>
                        <div class="flex flex-col text-right">
                            <span class="text-xs text-gray-500 font-medium">Statut Actuel</span>
                            <span class="font-bold text-sm {{ $candidature->statut->colorClass() }}">
                                {{ $candidature->statut->label() }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-between items-center">
                        <span class="text-xs text-gray-400"><i class="bi bi-clock mr-1"></i> Postulé {{ $candidature->date_candidature->diffForHumans() }}</span>
                        <!-- Direct link to standard view where company can Accept/Reject -->
                        <a href="{{ route('entreprise.candidatures.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">
                            Gérer &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-1 sm:grid-cols-2 lg:col-span-3 text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100">
                <i class="bi bi-inbox text-5xl text-gray-300"></i>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Aucun candidat</h3>
                <p class="text-gray-500">Personne n'a encore postulé à vos offres avec ces critères.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $candidates->links() }}
    </div>
</div>
@endsection
