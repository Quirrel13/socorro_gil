<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovimentacaoInvestimentoResource;
use App\Models\MovimentacaoInvestimento;
use Illuminate\Support\Facades\Gate;

class MovimentacaoInvestimentoController extends Controller
{
    public function show(MovimentacaoInvestimento $movimentacao): MovimentacaoInvestimentoResource
    {
        Gate::authorize('view', $movimentacao);

        $movimentacao->load('investimento.tipo');

        return new MovimentacaoInvestimentoResource($movimentacao);
    }
}