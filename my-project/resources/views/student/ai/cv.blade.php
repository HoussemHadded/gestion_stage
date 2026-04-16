@extends('layouts.app')

@section('title', 'Mon CV (Parseur IA)')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 border-b-2 border-indigo-500 pb-2 inline-block">
        <i class="bi bi-file-earmark-person text-indigo-600 mr-2"></i>Mon CV
    </h1>
    <p class="text-gray-500 mt-2">Analysez votre profil à l'aide de l'Intelligence Artificielle pour en extraire vos compétences clés.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Left Column: Input Form --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800"><i class="bi bi-fonts mr-2"></i>Texte de votre CV</h3>
            </div>
            
            <form action="{{ route('student.cv.parse') }}" method="POST" class="p-6">
                @csrf
                
                <div class="mb-4">
                    <label for="cv_text" class="block text-sm font-medium text-gray-700 mb-2">
                        Copiez-collez le contenu de votre CV ici (expériences, formations, compétences...)
                    </label>
                    <textarea id="cv_text" name="cv_text" rows="12" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 p-3 bg-gray-50"
                        placeholder="Développeur web passionné...&#10;&#10;Expériences :&#10;- Stage chez XYZ : Création d'une API avec Laravel...&#10;&#10;Compétences :&#10;PHP, MySQL, Vue.js..."
                    >{{ old('cv_text', $student->cv_text) }}</textarea>
                    @error('cv_text')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        <i class="bi bi-info-circle mr-1"></i>
                        L'IA va lire ce texte pour mettre à jour votre profil.
                    </p>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded shadow-sm inline-flex items-center transition">
                        <i class="bi bi-magic mr-2"></i> Extraire les compétences (IA)
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Right Column: Extracted Skills --}}
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-bold text-gray-800"><i class="bi bi-tags mr-2"></i>Compétences extraites</h3>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ count($skills) }}</span>
            </div>
            
            <div class="p-6">
                @if($skills->isEmpty())
                    <div class="text-center py-6 text-gray-500">
                        <i class="bi bi-box-seam text-4xl mb-3 block text-gray-300"></i>
                        <p class="text-sm">Aucune compétence enregistrée.</p>
                        <p class="text-xs mt-2">Collez votre CV et cliquez sur "Extraire" pour générer votre profil.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach(['expert' => 'Expert', 'advanced' => 'Avancé', 'intermediate' => 'Intermédiaire', 'beginner' => 'Débutant'] as $levelKey => $levelLabel)
                            
                            @php
                                $levelSkills = $skills->where('pivot.level', $levelKey);
                            @endphp
                            
                            @if($levelSkills->isNotEmpty())
                                <div>
                                    <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wide border-b pb-1 mb-2">
                                        {{ $levelLabel }}
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($levelSkills as $skill)
                                            @php
                                                // Colorize based on level
                                                $badgeColor = match($levelKey) {
                                                    'expert' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                    'advanced' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                    'intermediate' => 'bg-green-100 text-green-800 border-green-200',
                                                    default => 'bg-gray-100 text-gray-800 border-gray-200'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-medium border {{ $badgeColor }}">
                                                {{ $skill->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                        @endforeach
                    </div>
                    
                    <div class="mt-8 text-center border-t pt-4">
                        <a href="{{ route('student.match.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="bi bi-arrow-right-circle mr-1"></i> Utiliser ces compétences pour trouver un stage
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
</div>
@endsection
