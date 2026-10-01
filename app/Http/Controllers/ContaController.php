<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContaRequest;
use App\Models\Conta;
use App\Services\ContaService;
use App\Services\UserService;
use Illuminate\Support\Facades\Gate;

class ContaController extends Controller
{
    public function __construct(
        protected ContaService $service,
        protected UserService $userService
    ) {}

    public function index()
    {
        Gate::authorize('viewAny', Conta::class);

        $user = auth()->user();

        if ($user->role->name === 'gerente_conta') {
            $contas = $this->service->listarPorGerente($user->id);
        } else {
            $contas = $this->service->all([
                'cliente',
                'gerente'
            ]);
        }

        return view('contas.index', compact('contas'));
    }

    public function create()
    {
        Gate::authorize('create', Conta::class);

        $clientes = $this->userService->listarClientes();
        $gerentes = $this->userService->listarGerentesDeConta();

        return view('contas.create', compact(
            'clientes',
            'gerentes'
        ));
    }

    public function store(ContaRequest $request)
    {
        Gate::authorize('create', Conta::class);

        $this->service->criarConta(
            $request->validated()
        );

        return redirect()
            ->route('conta.index')
            ->with('success', 'Conta criada com sucesso.');
    }

    public function edit(Conta $conta)
    {
        Gate::authorize('update', $conta);

        $clientes = $this->userService->listarClientes();
        $gerentes = $this->userService->listarGerentesDeConta();

        return view('contas.edit', compact(
            'conta',
            'clientes',
            'gerentes'
        ));
    }

    public function update(
        ContaRequest $request,
        Conta $conta
    ) {
        Gate::authorize('update', $conta);

        $this->service->update(
            $request->validated(),
            $conta->id
        );

        return redirect()
            ->route('conta.index')
            ->with('success', 'Conta atualizada com sucesso.');
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
}