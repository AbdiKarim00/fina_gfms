<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\Contracts\AuthenticationServiceInterface;
use App\Services\Contracts\OrganizationalValidationServiceInterface;
use App\Services\Contracts\RoleHierarchyServiceInterface;
use App\Services\Contracts\TestServiceInterface;
use App\Services\OrganizationalValidationService;
use App\Services\RoleHierarchyService;
use App\Services\TestService;
use Illuminate\Support\ServiceProvider;

class ServiceLayerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind interfaces to implementations
        $this->app->bind(
            AuthenticationServiceInterface::class,
            AuthService::class
        );

        $this->app->bind(
            RoleHierarchyServiceInterface::class,
            RoleHierarchyService::class
        );

        $this->app->bind(
            OrganizationalValidationServiceInterface::class,
            OrganizationalValidationService::class
        );

        $this->app->bind(
            TestServiceInterface::class,
            TestService::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
