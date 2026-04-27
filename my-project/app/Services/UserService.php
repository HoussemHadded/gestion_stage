<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function __construct(
        private CacheService $cacheService
    ) {}

    public function store(array $data): User
    {
        // Remove confirmation field — it must not reach the DB layer
        unset($data['password_confirmation']);

        // Hash password explicitly (model cast removed to avoid double-hashing)
        $data['password'] = Hash::make($data['password']);

        Log::info('[UserService] About to insert user into database', [
            'payload' => Arr::except($data, ['password']),
        ]);

        try {
            $user = User::create($data);
        } catch (\Throwable $e) {
            Log::error('[UserService] User::create() failed', [
                'error'   => $e->getMessage(),
                'payload' => Arr::except($data, ['password']),
            ]);
            throw $e;
        }

        if (! $user->exists || ! $user->id) {
            Log::error('[UserService] User::create() returned without a persisted ID', [
                'payload' => Arr::except($data, ['password']),
            ]);
            throw new \RuntimeException('Registration failed: user was not inserted into the database.');
        }

        Log::info('[UserService] New user successfully inserted into database', [
            'id'    => $user->id,
            'email' => $user->email,
            'role'  => $user->role,
        ]);

        $this->cacheService->forgetUsers();

        return $user;
    }

    public function update(User $user, array $data): bool
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $result = $user->update($data);

        $this->cacheService->forgetUsers();

        return $result;
    }

    public function delete(User $user): bool
    {
        $result = $user->delete();

        $this->cacheService->forgetUsers();
        $this->cacheService->forgetOffres();
        $this->cacheService->forgetCandidatures();

        return $result;
    }
}
