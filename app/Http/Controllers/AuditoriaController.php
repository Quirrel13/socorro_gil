<?php

namespace App\Http\Controllers;

use App\Services\AuditService;
use Illuminate\Support\Facades\Gate;
use OwenIt\Auditing\Models\Audit;

class AuditoriaController extends Controller
{
    public function __construct(
        protected AuditService $service
    ) {
    }

    public function index()
    {
        Gate::authorize('viewAny', Audit::class);

        $logs = $this->service->listarAtividadesDeGerentes();

        return view('auditoria.index', compact('logs'));
    }
}