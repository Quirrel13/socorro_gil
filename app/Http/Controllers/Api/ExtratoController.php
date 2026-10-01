<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExtratoRequest;
use App\Services\ContaService;
use App\Services\ExtratoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class ExtratoController extends Controller
{
    public function __construct(
        protected ExtratoService $service,
        protected ContaService $contaService
    ) {
    }

    public function index(ExtratoRequest $request): JsonResponse
    {
        $conta = $this->contaService->contaDoCliente($request->user()->id);

        Gate::authorize('extrato', $conta);

        $inicio = $request->validated('data_inicial');
        $fim = $request->validated('data_final');

        return response()->json([
            'conta' => $conta->id,
            'periodo' => [
                'data_inicial' => $inicio,
                'data_final' => $fim,
            ],
            // cada linha: data, tipo, descricao, valor (negativo = saiu), sentido
            'movimentacoes' => $this->service->gerar($conta->id, $inicio, $fim),
        ]);
    }
}