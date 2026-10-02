<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\RegraDeNegocioException;
use App\Http\Controllers\Controller;
use App\Http\Requests\MovimentacaoInvestimentoRequest;
use App\Http\Resources\InvestimentoResource;
use App\Http\Resources\MovimentacaoInvestimentoResource;
use App\Models\Investimento;
use App\Services\ContaService;
use App\Services\InvestimentoService;
use App\Services\MovimentacaoInvestimentoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class InvestimentoController extends Controller
{
    public function __construct(
        protected InvestimentoService $service,
        protected MovimentacaoInvestimentoService $movimentacaoService,
        protected ContaService $contaService
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Investimento::class);

        $conta = $this->contaService->contaDoCliente($request->user()->id);

          if ($conta->bloqueado) {
            throw new RegraDeNegocioException('Conta bloqueada: apenas o saldo pode ser visualizado.');
        }

        return InvestimentoResource::collection(
            $this->service->listarPorConta($conta->id)
        );
    }

    public function show(Investimento $investimento): InvestimentoResource
    {
        Gate::authorize('view', $investimento);

        $investimento->load('tipo');

        return new InvestimentoResource($investimento);
    }

    public function aplicar(
        MovimentacaoInvestimentoRequest $request,
        Investimento $investimento
    ): JsonResponse {
        Gate::authorize('aplicar', $investimento);

        $movimentacao = $this->movimentacaoService->aplicar(
            $investimento->id,
            $request->validated('valor')
        );

        $movimentacao->load('investimento.tipo');

        return (new MovimentacaoInvestimentoResource($movimentacao))
            ->response()
            ->setStatusCode(201);
    }

    public function resgatar(
        MovimentacaoInvestimentoRequest $request,
        Investimento $investimento
    ): JsonResponse {
        Gate::authorize('resgatar', $investimento);

        $movimentacao = $this->movimentacaoService->resgatar(
            $investimento->id,
            $request->validated('valor')
        );

        $movimentacao->load('investimento.tipo');

        return (new MovimentacaoInvestimentoResource($movimentacao))
            ->response()
            ->setStatusCode(201);
    }
}