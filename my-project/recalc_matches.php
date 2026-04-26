<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Offre;
use App\Services\MatchingService;

$service = app(MatchingService::class);
$students = User::where('role', 'etudiant')->get();
$offres = Offre::all();

foreach ($students as $student) {
    echo "Recalculating for student: {$student->name}...\n";
    foreach ($offres as $o) {
        $result = $service->calculate($student, $o);
        if ($result) {
            echo "Offer #{$o->id} - Score: {$result['score']}%\n";
        }
    }
}
echo "Done.\n";
