<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimentacaoInvestimentoRequest;
use App\Models\Investimento;
use App\Services\InvestimentoService;
use App\Services\MovimentacaoInvestimentoService;
use Illuminate\Support\Facades\Gate;

class InvestimentoController extends Controller
{
    public function __construct(
        protected InvestimentoService $service,
        protected MovimentacaoInvestimentoService $movimentacaoService
    ) {}

    public function show(Investimento $investimento)
    {
        Gate::authorize('view', $investimento);

        return view('investimentos.show', compact('investimento'));
    }

    public function aplicar(
        MovimentacaoInvestimentoRequest $request,
        Investimento $investimento
    ) {
        Gate::authorize('aplicar', $investimento);

        $this->movimentacaoService->aplicar(
            $investimento->id,
            $request->validated()['valor']
        );

        return redirect()
            ->back()
            ->with('success', 'Aplicação realizada com sucesso.');
    }

    public function resgatar(
        MovimentacaoInvestimentoRequest $request,
        Investimento $investimento
    ) {
        Gate::authorize('resgatar', $investimento);

        $this->movimentacaoService->resgatar(
            $investimento->id,
            $request->validated()['valor']
        );

        return redirect()
            ->back()
            ->with('success', 'Resgate realizado com sucesso.');
    }
}