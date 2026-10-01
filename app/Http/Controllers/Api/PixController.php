<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PixRequest;
use App\Http\Resources\PixResource;
use App\Models\Pix;
use App\Services\PixService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PixController extends Controller
{
    public function __construct(
        protected PixService $service
    ) {
    }

    public function store(PixRequest $request): JsonResponse
    {
        Gate::authorize('create', Pix::class);

        $dados = $request->validated();

        $pix = $this->service->realizarPix(
            $request->user(),
            (int) $dados['conta_destino_id'],
            $dados['valor'],
            $dados['descricao'] ?? null
        );

        $pix->load(['contaOrigem.cliente', 'contaDestino.cliente']);

        return (new PixResource($pix))->response()->setStatusCode(201);
    }

    public function show(Pix $pix): PixResource
    {
        Gate::authorize('view', $pix);

        $pix->load(['contaOrigem.cliente', 'contaDestino.cliente']);

        return new PixResource($pix);
    }
}