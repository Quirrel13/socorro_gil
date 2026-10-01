<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContaResource;
use App\Services\ContaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContaController extends Controller
{
    public function __construct(
        protected ContaService $service
    ) {
    }

    public function show(Request $request): ContaResource
    {
        $conta = $this->service->contaDoCliente($request->user()->id);

        Gate::authorize('verSaldo', $conta);

        return new ContaResource($conta);
    }
}