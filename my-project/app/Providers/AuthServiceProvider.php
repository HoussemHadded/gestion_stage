<?php

namespace App\Providers;

use App\Models\Candidature;
use App\Models\Offre;
use App\Models\User;
use App\Policies\CandidaturePolicy;
use App\Policies\OffrePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class        => UserPolicy::class,
        Offre::class       => OffrePolicy::class,
        Candidature::class => CandidaturePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
