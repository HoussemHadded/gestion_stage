<?php

namespace App\Services;

use App\Models\Offre;

class OffreService
{
    public function __construct(
        private CacheService $cacheService
    ) {}

    public function store(array $data): Offre
    {
        $offre = Offre::create($data);

        $this->cacheService->forgetOffres();

        return $offre;
    }

    public function update(Offre $offre, array $data): bool
    {
        $result = $offre->update($data);

        $this->cacheService->forgetOffres();

        return $result;
    }

    public function delete(Offre $offre): bool
    {
        $result = $offre->delete();

        $this->cacheService->forgetOffres();
        $this->cacheService->forgetCandidatures();

        return $result;
    }
}
