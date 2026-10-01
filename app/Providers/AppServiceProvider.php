<?php

namespace App\Providers;

use App\Policies\AuditPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(config('audit.implementation'), AuditPolicy::class);
    }
}