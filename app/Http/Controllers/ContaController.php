<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContaRequest;
use App\Http\Requests\ContaUpdateRequest;
use App\Http\Requests\ExtratoRequest;
use App\Models\Conta;
use App\Services\ContaService;
use App\Services\ExtratoService;
use Illuminate\Support\Facades\Gate;

class ContaController extends Controller
{
    public function __construct(
        protected ContaService $service,
        protected ExtratoService $extratoService
    ) {
    }

    public function index()
    {
        Gate::authorize('viewAny', Conta::class);

        $contas = $this->service->listarPorGerente(auth()->id());

        return view('contas.index', compact('contas'));
    }

    public function create()
    {
        Gate::authorize('create', Conta::class);

        return view('contas.create');
    }

    public function store(ContaRequest $request)
    {
        Gate::authorize('create', Conta::class);

        $this->service->abrirConta($request->validated(), auth()->id());

        return redirect()
            ->route('conta.index')
            ->with('success', 'Conta criada com sucesso.');
    }

    public function show(Conta $conta)
    {
        Gate::authorize('view', $conta);

        $conta->load(['cliente', 'investimentos.tipo', 'solicitacoes']);

        return view('contas.show', compact('conta'));
    }

    public function edit(Conta $conta)
    {
        Gate::authorize('update', $conta);

        $conta->load('cliente');

        return view('contas.edit', compact('conta'));
    }

    public function update(ContaUpdateRequest $request, Conta $conta)
    {
        Gate::authorize('update', $conta);

        // altera apenas dados do cliente; limite só muda por solicitação aprovada
        $this->service->atualizarDadosCliente($conta->id, $request->validated());

        return redirect()
            ->route('conta.index')
            ->with('success', 'Dados do cliente atualizados com sucesso.');
    }

    public function destroy(Conta $conta)
    {
        Gate::authorize('delete', $conta);

        // exige saldo e investimentos zerados
        $this->service->remover($conta->id);

        return redirect()
            ->route('conta.index')
            ->with('success', 'Conta removida com sucesso.');
    }

    public function bloquear(Conta $conta)
    {
        Gate::authorize('bloquear', $conta);

        $this->service->bloquear($conta->id);

        return redirect()
            ->back()
            ->with('success', 'Conta bloqueada com sucesso.');
    }

    public function desbloquear(Conta $conta)
    {
        Gate::authorize('desbloquear', $conta);

        $this->service->desbloquear($conta->id);

        return redirect()
            ->back()
            ->with('success', 'Conta desbloqueada com sucesso.');
    }

    // Extrato do cliente visto pelo gerente (pix + aplicações/resgates)
    public function extrato(ExtratoRequest $request, Conta $conta)
    {
        Gate::authorize('extrato', $conta);

        $conta->load('cliente');

        $movimentacoes = $this->extratoService->gerar(
            $conta->id,
            $request->validated('data_inicial'),
            $request->validated('data_final')
        );

        return view('contas.extrato', compact('conta', 'movimentacoes'));
    }
}