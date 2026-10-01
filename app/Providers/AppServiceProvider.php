<?php

namespace App\Providers;

use App\Enums\NomeRole;
use App\Models\Solicitacao;
use App\Policies\AuditPolicy;
use App\Services\SolicitacaoService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
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

        View::composer('components.ifb.layout', function ($view) {
            $user = auth()->user();
            $pendentes = 0;

            if ($user && $user->can('viewAny', Solicitacao::class)) {
                $pendentes = app(SolicitacaoService::class)->contarPendentes(
                    $user->temRole(NomeRole::GERENTE_GERAL) ? null : $user->id
                );
            }

            $view->with('pendentes', $pendentes);
        });
    }
}