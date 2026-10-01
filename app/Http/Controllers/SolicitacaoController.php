<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitacaoRequest;
use App\Models\Solicitacao;
use App\Services\SolicitacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SolicitacaoController extends Controller
{
    public function __construct(
        protected SolicitacaoService $service
    ) {
    }

    public function index()
    {
        Gate::authorize('viewAny', Solicitacao::class);

        $user = auth()->user();

        if ($user->role->name === 'gerente_geral') {
            $solicitacoes = $this->service->all([
                'conta',
                'gerente'
            ]);
        } else {
            $solicitacoes = $this->service->listarPorGerente(
                $user->id
            );
        }

        return view('solicitacoes.index', compact('solicitacoes'));
    }

    public function create()
    {
        Gate::authorize('create', Solicitacao::class);

        return view('solicitacoes.create');
    }

    public function store(SolicitacaoRequest $request)
    {
        Gate::authorize('create', Solicitacao::class);

        $dados = $request->validated();

        $this->service->solicitar(
            $dados['conta_id'],
            $dados['limite']
        );

        return redirect()
            ->route('solicitacao.index')
            ->with('success', 'Solicitação criada com sucesso.');
    }

    public function aprovar(Solicitacao $solicitacao)
    {
        Gate::authorize('aprovar', $solicitacao);

        $this->service->aprovar(
            $solicitacao->id,
            auth()->id()
        );

        return redirect()
            ->back()
            ->with('success', 'Solicitação aprovada com sucesso.');
    }

    public function recusar(Request $request, Solicitacao $solicitacao)
    {
        Gate::authorize('recusar', $solicitacao);

        $request->validate([
            'motivo_recusa' => 'required|string|max:255',
        ], [
            'required' => 'O preenchimento deste campo é obrigatório!',
            'string' => 'Este campo deve ser um texto!',
            'max' => 'Este campo possui tamanho máximo de :max caracteres!',
        ]);

        $this->service->recusar(
            $solicitacao->id,
            $request->motivo_recusa
        );

        return redirect()
            ->back()
            ->with('success', 'Solicitação recusada com sucesso.');
    }
}