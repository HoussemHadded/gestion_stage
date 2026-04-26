<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Candidature;
use App\Services\MatchService;

$candidatures = Candidature::with(['student.skills', 'offre.skills'])->take(5)->get();

function getFeaturesDebug($model, bool $isOffre) {
    $raw = strtolower($model->titre . ' ' . ($isOffre ? $model->description : $model->cv_text));
    $dict = [
        'php', 'laravel', 'symfony', 'wordpress', 'javascript', 'typescript', 'node.js', 'react', 'vue', 'angular',
        'next.js', 'python', 'django', 'flask', 'fastapi', 'sql', 'mysql', 'postgresql', 'mongodb', 'docker', 'kubernetes',
        'aws', 'azure', 'gcp', 'git', 'linux', 'html', 'css', 'tailwind', 'figma', 'ui/ux', 'tensorflow', 'pytorch',
        'nlp', 'data science', 'marketing', 'finance', 'excel', 'agile', 'scrum', 'testing', 'qa', 'cybersécurité',
        'java', 'spring', 'hibernate', 'c++', 'c#', 'dotnet', 'ruby', 'rails', 'go', 'rust', 'flutter', 'kotlin'
    ];
    $skills = $model->skills()->pluck('name')->map(fn($n) => strtolower(trim($n)))->toArray();
    $nlp = array_values(array_filter($dict, fn($w) => preg_match('/\b' . preg_quote($w, '/') . '\b/i', $raw)));
    
    return [
        'explicit_skills' => $skills,
        'nlp_skills' => $nlp,
        'all_skills' => array_unique(array_merge($skills, $nlp))
    ];
}

foreach ($candidatures as $c) {
    echo "Candidature ID: {$c->id}\n";
    $offerData = getFeaturesDebug($c->offre, true);
    $studentData = getFeaturesDebug($c->student, false);
    
    echo "Offer Skills (req): " . count($offerData['all_skills']) . " - [" . implode(', ', $offerData['all_skills']) . "]\n";
    echo "Student Skills (has): " . count($studentData['all_skills']) . " - [" . implode(', ', $studentData['all_skills']) . "]\n";
    
    $inter = array_intersect($offerData['all_skills'], $studentData['all_skills']);
    echo "Intersection: " . count($inter) . " - [" . implode(', ', $inter) . "]\n";
    if (count($offerData['all_skills']) == 0) {
        $skillScore = empty($studentData['all_skills']) ? 30 : 60;
    } else {
        $skillScore = (count($inter) / count($offerData['all_skills'])) * 60;
    }
    echo "Skill Score: {$skillScore}\n";
    echo "-------------------\n";
}
