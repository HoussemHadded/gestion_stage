@extends('layouts.app')

@section('title', 'Classement des Étudiants')

@section('content')
<div class="max-w-5xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">Classement Global 🏆</h1>
        <p class="mt-3 text-lg text-gray-500">Découvrez les meilleurs profils de la plateforme, classés par notre IA en fonction de leurs compétences et de leur taux de réussite.</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rang</th>
                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Étudiant</th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Score CV IA</th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Match Moyen</th>
                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Candidatures Acceptées</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($topStudents as $index => $student)
                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full font-bold text-lg 
                            @if($index === 0) bg-yellow-100 text-yellow-600 border-2 border-yellow-400 shadow-sm
                            @elseif($index === 1) bg-gray-100 text-gray-600 border-2 border-gray-300 shadow-sm
                            @elseif($index === 2) bg-orange-100 text-orange-600 border-2 border-orange-300 shadow-sm
                            @else bg-blue-50 text-blue-600
                            @endif">
                            @if($index === 0) 🥇
                            @elseif($index === 1) 🥈
                            @elseif($index === 2) 🥉
                            @else #{{ $index + 1 }}
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex justify-center items-center font-bold text-indigo-700">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-bold text-gray-900">{{ $student->name }}</div>
                                <div class="text-sm text-gray-500">{{ $student->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-indigo-100 text-indigo-800">
                            {{ $student->cv_score ?? 0 }} / 100
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        @php
                            $avgMatch = round($student->average_match ?? 0);
                        @endphp
                        <span class="text-sm font-semibold {{ $avgMatch >= 80 ? 'text-green-600' : ($avgMatch >= 50 ? 'text-yellow-600' : 'text-gray-500') }}">
                            {{ $avgMatch }}%
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="text-sm font-medium text-gray-900">{{ $student->accepted_candidatures_count }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($topStudents->isEmpty())
        <div class="text-center py-12">
            <i class="bi bi-trophy text-4xl text-gray-300"></i>
            <p class="mt-2 text-gray-500">Le classement est en cours de calcul...</p>
        </div>
        @endif
    </div>
</div>
@endsection
