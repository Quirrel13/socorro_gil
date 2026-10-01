<?php

namespace App\Http\Controllers;

use App\Models\MovimentacaoInvestimento;
use Illuminate\Support\Facades\Gate;

class MovimentacaoInvestimentoController extends Controller
{
    public function show(
        MovimentacaoInvestimento $movimentacao
    ) {
        Gate::authorize('view', $movimentacao);

        $movimentacao->load([
            'investimento.conta',
            'investimento.tipo'
        ]);

        return view(
            'movimentacoes_investimentos.show',
            compact('movimentacao')
        );
    }
}