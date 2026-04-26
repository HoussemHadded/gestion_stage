<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Candidature;
use App\Services\MatchService;

echo "=== VERIFYING IMPROVED SCORE VARIANCE ===\n\n";

$service = app(MatchService::class);
$candidatures = Candidature::with(['student', 'offre'])->get();
$scores = [];

printf("%-10s | %-40s | %-10s | %-10s\n", "ID", "Offre", "Old Score", "New Score");
echo str_repeat("-", 80) . "\n";

foreach ($candidatures as $c) {
    $old = $c->match_percentage;
    $new = $service->calculate($c);
    $scores[] = $new;
    
    printf("%-10d | %-40s | %-10d | %-10d\n", $c->id, substr($c->offre->titre ?? 'N/A', 0, 40), $old, $new);
}

echo "\n--- Statistical Analysis ---\n";
echo "Count:    " . count($scores) . "\n";
echo "Min:      " . min($scores) . "%\n";
echo "Max:      " . max($scores) . "%\n";
echo "Average:  " . round(array_sum($scores) / count($scores), 1) . "%\n";
echo "Variance: " . count(array_unique($scores)) . " unique values\n";
echo "\n=== DONE ===\n";
