<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Candidature;
use App\Services\MatchService;

echo "=== RECALCULATING ALL MATCH SCORES ===\n\n";

$service      = app(MatchService::class);
$candidatures = Candidature::with(['student.skills', 'offre.skills'])->get();
$results      = [];

foreach ($candidatures as $c) {
    $old   = $c->match_percentage;
    $new   = $service->calculate($c);
    $diff  = $new - $old;
    $results[] = $new;
    $diffStr   = $diff > 0 ? "+{$diff}" : (string) $diff;
    $flag      = $diff !== 0 ? " ← CHANGED ({$diffStr})" : '';
    printf(
        "Cand#%-3d | %-35s | Old: %3d%% → New: %3d%%%s\n",
        $c->id,
        substr($c->offre->titre ?? 'N/A', 0, 35),
        $old,
        $new,
        $flag
    );
}

echo "\n--- Score Distribution ---\n";
echo "Min:    " . min($results) . "%\n";
echo "Max:    " . max($results) . "%\n";
echo "Avg:    " . round(array_sum($results) / count($results), 1) . "%\n";
echo "Unique: " . count(array_unique($results)) . " distinct values\n";
echo "\n=== DONE ===\n";
