<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private CacheService $cacheService
    ) {}

    public function store(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

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
