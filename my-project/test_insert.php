<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $data = [
        'name' => 'test2',
        'email' => 'test2@example.com',
        'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
        'role' => \App\Enums\UserRole::Etudiant
    ];
    $user = \App\Models\User::create($data);
    echo "Success: " . $user->id . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
