<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Candidature;
use App\Models\User;
use App\Models\Offre;
use Illuminate\Support\Facades\DB;

echo "=== MATCH SCORE DIAGNOSTIC ===\n\n";

// 1. Check data availability
echo "[1] Total candidatures: " . Candidature::count() . "\n";
echo "[1] Candidatures with match_percentage NULL: " . Candidature::whereNull('match_percentage')->count() . "\n";
echo "[1] Candidatures with match_percentage = 0:  " . Candidature::where('match_percentage', 0)->count() . "\n";

// 2. Sample 5 candidatures and show their data
echo "\n[2] Sample candidatures:\n";
$candidatures = Candidature::with(['student.skills', 'offre.skills'])->take(5)->get();

foreach ($candidatures as $c) {
    $student = $c->student;
    $offre   = $c->offre;

    $studentSkills = $student ? $student->skills->pluck('name')->toArray() : [];
    $offerSkills   = $offre   ? $offre->skills->pluck('name')->toArray()   : [];
    $cvLength      = strlen($student->cv_text ?? '');
    $cvPreview     = substr(strtolower($student->cv_text ?? ''), 0, 80);

    echo "  Cand#{$c->id} | Match={$c->match_percentage}%\n";
    echo "    Student: " . ($student->name ?? 'N/A') . " | cv_text length: {$cvLength}\n";
    echo "    Student skills in DB: [" . implode(', ', $studentSkills) . "]\n";
    echo "    Offer:   " . ($offre->titre ?? 'N/A') . "\n";
    echo "    Offer skills in DB:   [" . implode(', ', $offerSkills) . "]\n";
    echo "    CV preview: \"{$cvPreview}\"\n\n";
}

// 3. Check if students actually have cv_text
echo "[3] Students with cv_text populated: "
    . User::where('role', 'etudiant')->whereNotNull('cv_text')->where('cv_text', '!=', '')->count()
    . " / " . User::where('role', 'etudiant')->count() . " total students\n";

// 4. Check pivot tables
echo "[4] Rows in student_skills pivot: " . DB::table('student_skills')->count() . "\n";
echo "[4] Rows in offer_skills pivot:   " . DB::table('offer_skills')->count() . "\n";

// 5. Re-run scoring on first candidature with debug
echo "\n[5] Simulating MatchService on first candidature:\n";
$c = Candidature::with(['student.skills', 'offre.skills'])->whereNotNull('offre_id')->first();
if ($c) {
    $student = $c->student;
    $offre   = $c->offre;

    $offerSkills   = $offre->skills()->pluck('name')->map(fn($n) => strtolower(trim($n)))->toArray();
    $studentSkills = $student->skills()->pluck('name')->map(fn($n) => strtolower(trim($n)))->toArray();
    $offerText     = strtolower($offre->titre . ' ' . $offre->description);
    $studentCv     = strtolower($student->cv_text ?? '');

    echo "  offerSkills:   [" . implode(', ', $offerSkills) . "]\n";
    echo "  studentSkills: [" . implode(', ', $studentSkills) . "]\n";
    echo "  offerText length: " . strlen($offerText) . " chars\n";
    echo "  cv_text length:   " . strlen($studentCv) . " chars\n";

    $matched = array_intersect($offerSkills, $studentSkills);
    echo "  Intersected skills: [" . implode(', ', $matched) . "]\n";

    $score = 0;
    if (count($offerSkills) > 0) {
        $ratio = count($matched) / count($offerSkills);
        $score += $ratio * 60;
        echo "  Skill score: " . ($ratio * 60) . " (ratio={$ratio})\n";
    } else {
        $vol = min(count($studentSkills) * 3, 40);
        $score += $vol;
        echo "  Skill score (fallback): {$vol}\n";
    }

    $expWords    = ['junior', 'senior', 'lead', 'expert', 'débutant', 'stage', 'alternance'];
    $offerExp    = array_filter($expWords, fn($w) => str_contains($offerText, $w));
    $studentExp  = array_filter($expWords, fn($w) => str_contains($studentCv, $w));
    $expMatch    = array_intersect($offerExp, $studentExp);
    if (count($offerExp) > 0) {
        $expScore = (count($expMatch) / count($offerExp)) * 25;
        $score += $expScore;
        echo "  Exp score: {$expScore} | offer has: [" . implode(',', $offerExp) . "] student has: [" . implode(',', $studentExp) . "]\n";
    } else {
        $score += 15;
        echo "  Exp score (fallback): 15\n";
    }

    $buzz       = ['laravel','php','react','vue','javascript','python','java','marketing','design','finance','agile','sql'];
    $offerBuzz  = array_filter($buzz, fn($w) => str_contains($offerText, $w));
    $studentBuzz= array_filter($buzz, fn($w) => str_contains($studentCv, $w));
    $buzzMatch  = array_intersect($offerBuzz, $studentBuzz);
    if (count($offerBuzz) > 0) {
        $buzzScore = (count($buzzMatch) / count($offerBuzz)) * 15;
        $score += $buzzScore;
        echo "  Buzz score: {$buzzScore} | offer has: [" . implode(',', $offerBuzz) . "] student has: [" . implode(',', $studentBuzz) . "]\n";
    } else {
        $score += 10;
        echo "  Buzz score (fallback): 10\n";
    }

    echo "  TOTAL COMPUTED SCORE: " . round($score) . " (stored: {$c->match_percentage})\n";
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
