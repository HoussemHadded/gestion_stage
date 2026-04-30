<?php

use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->select('id', 'role')->orderBy('id')->chunkById(200, function ($users): void {
            foreach ($users as $user) {
                $raw = (string) $user->role;
                $normalized = mb_strtolower(trim($raw));
                $normalized = str_replace(["\r", "\n", "\t"], '', $normalized);

                if ($normalized === 'student') {
                    $normalized = UserRole::Etudiant->value;
                }

                if (UserRole::tryFrom($normalized) !== null && $normalized !== $raw) {
                    DB::table('users')->where('id', $user->id)->update(['role' => $normalized]);
                }
            }
        });
    }

    public function down(): void
    {
        // Data normalization only; no safe generic rollback.
    }
};