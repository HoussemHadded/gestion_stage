<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Candidature;

// Must match MatchService constants exactly
$SKILL_DICT = [
    'php','laravel','symfony','wordpress','codeigniter',
    'javascript','typescript','node.js','nodejs','express',
    'react','vue','angular','next.js','nuxt',
    'python','django','flask','fastapi','tensorflow','pytorch',
    'machine learning','deep learning','nlp','data science',
    'pandas','numpy','scikit','keras','r','matlab',
    'sql','mysql','postgresql','mongodb','redis','elasticsearch',
    'oracle','sqlite','mariadb',
    'docker','kubernetes','aws','azure','gcp','linux',
    'git','ci/cd','jenkins','ansible','terraform',
    'android','ios','swift','kotlin','flutter','react native',
    'html','css','sass','tailwind','figma','photoshop','ui/ux',
    'excel','powerpoint','sap','crm','erp','marketing',
    'finance','comptabilité','accounting','management',
    'réseaux','network','cisco','cybersécurité','cybersecurity',
    'firewall','vpn','tcp/ip',
    'testing','selenium','junit','qa','quality assurance',
    'agile','scrum','kanban','jira','trello',
];
$EXP_WORDS = ['junior','senior','lead','expert','débutant','confirmé','stage','internship','alternance','cdi','cdd','freelance','expérience','experience','1 an','2 ans','3 ans','5 ans'];
$EDU_WORDS = ['bac+5','master','mastère','ingénieur','engineer','msc','bac+3','licence','bachelor','bsc','bac+2','dut','bts','doctorat','phd'];

$extract = fn(string $text, array $dict) => array_values(array_filter($dict, fn($w) => str_contains(strtolower($text), $w)));

echo "=== SCORE SATURATION DIAGNOSTIC ===\n\n";

$candidatures = Candidature::with(['student.skills','offre.skills'])->get();

foreach ($candidatures as $c) {
    $student = $c->student;
    $offre   = $c->offre;
    if (!$student || !$offre) continue;

    $offerText  = strtolower($offre->titre . ' ' . ($offre->description ?? ''));
    $studentCv  = strtolower($student->cv_text ?? '');

    $offerSkillsDb   = $offre->skills()->pluck('name')->map(fn($n)=>strtolower(trim($n)))->toArray();
    $studentSkillsDb = $student->skills()->pluck('name')->map(fn($n)=>strtolower(trim($n)))->toArray();

    if (count($offerSkillsDb) > 0) {
        $offerSkills   = $offerSkillsDb;
        $studentSkills = $studentSkillsDb;
    } else {
        $offerSkills   = $extract($offerText, $SKILL_DICT);
        $studentSkills = array_unique(array_merge($studentSkillsDb, $extract($studentCv, $SKILL_DICT)));
    }

    $matched = array_intersect($offerSkills, $studentSkills);

    // Current algo: recall = |match| / |offer|
    $recall = count($offerSkills) > 0 ? count($matched) / count($offerSkills) : 0;
    $skillScore_current = $recall * 60;

    // Proposed: Jaccard = |match| / |union|
    $union = array_unique(array_merge($offerSkills, $studentSkills));
    $jaccard = count($union) > 0 ? count($matched) / count($union) : 0;
    $skillScore_jaccard = $jaccard * 60;

    $offerExp   = $extract($offerText, $EXP_WORDS);
    $studentExp = $extract($studentCv,  $EXP_WORDS);
    $offerEdu   = $extract($offerText, $EDU_WORDS);
    $studentEdu = $extract($studentCv,  $EDU_WORDS);

    printf("Cand#%d | %s\n", $c->id, substr($offre->titre ?? '', 0, 40));
    printf("  Stored=%d%% | OfferSkills=[%s]\n", $c->match_percentage, implode(',', $offerSkills));
    printf("  StudentSkills=[%s]\n", implode(',', $studentSkills));
    printf("  Matched=[%s] | recall=%.2f | jaccard=%.2f\n", implode(',', $matched), $recall, $jaccard);
    printf("  SkillScore_current=%.1f | SkillScore_jaccard=%.1f\n", $skillScore_current, $skillScore_jaccard);
    printf("  OfferExp=[%s] StudentExp=[%s]\n", implode(',', $offerExp), implode(',', $studentExp));
    printf("  OfferEdu=[%s] StudentEdu=[%s]\n\n", implode(',', $offerEdu), implode(',', $studentEdu));
}
