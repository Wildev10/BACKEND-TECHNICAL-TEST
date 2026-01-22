<?php

namespace App\Providers;

use App\Models\EducationRequest;
use App\Policies\EducationRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Les correspondances modèle-policy pour l'application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        EducationRequest::class => EducationRequestPolicy::class,
    ];

    /**
     * Enregistre tous les services d'authentification / autorisation.
     */
    public function boot(): void
    {
        //
    }
}
