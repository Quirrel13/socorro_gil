<?php

namespace App\Http\Controllers;

use App\Enums\NomeRole;
use App\Exceptions\RegraDeNegocioException;
use App\Http\Requests\RecusarSolicitacaoRequest;
use App\Http\Requests\SolicitacaoRequest;
use App\Models\Solicitacao;
use App\Services\ContaService;
use App\Services\SolicitacaoService;
use Illuminate\Support\Facades\Gate;

class SolicitacaoController extends Controller
{
    public function __construct(
        protected SolicitacaoService $service,
        protected ContaService $contaService
    ) {
    }

    public function index()
    {
        Gate::authorize('viewAny', Solicitacao::class);

        $user = auth()->user();

        $solicitacoes = $user->temRole(NomeRole::GERENTE_GERAL)
            ? $this->service->listarTodas()
            : $this->service->listarPorGerente($user->id);

        return view('solicitacoes.index', compact('solicitacoes'));
    }

    public function create()
    {
        Gate::authorize('create', Solicitacao::class);

        // o gerente só pode escolher entre as próprias contas
        $contas = $this->contaService->listarPorGerente(auth()->id());

        return view('solicitacoes.create', compact('contas'));
    }

    public function store(SolicitacaoRequest $request)
    {
        Gate::authorize('create', Solicitacao::class);

        $dados = $request->validated();

        $conta = $this->contaService->find($dados['conta_id']);

        if (!$conta) {
            throw new RegraDeNegocioException('Conta não encontrada.');
        }

        Gate::authorize('solicitarLimite', $conta);

        $this->service->solicitar($conta->id, $dados['limite'], auth()->id());

        return redirect()
            ->route('solicitacao.index')
            ->with('success', 'Solicitação criada com sucesso.');
    }

    public function aprovar(Solicitacao $solicitacao)
    {
        Gate::authorize('aprovar', $solicitacao);

        $this->service->aprovar($solicitacao->id, auth()->id());

        return redirect()
            ->back()
            ->with('success', 'Solicitação aprovada com sucesso.');
    }

    public function recusar(RecusarSolicitacaoRequest $request, Solicitacao $solicitacao)
    {
        Gate::authorize('recusar', $solicitacao);

        $this->service->recusar(
            $solicitacao->id,
            $request->validated('motivo_recusa'),
            auth()->id()
        );

        return redirect()
            ->back()
            ->with('success', 'Solicitação recusada com sucesso.');
    }
}