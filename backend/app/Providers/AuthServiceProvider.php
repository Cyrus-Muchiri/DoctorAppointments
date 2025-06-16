<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Gate;
use App\Models\AppointmentRequest;
use App\Policies\AppointmentRequestPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        AppointmentRequest::class => AppointmentRequestPolicy::class,
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
