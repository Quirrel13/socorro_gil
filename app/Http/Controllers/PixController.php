<?php

namespace App\Http\Controllers;

use App\Http\Requests\PixRequest;
use App\Models\Pix;
use App\Services\ContaService;
use App\Services\PixService;
use Illuminate\Support\Facades\Gate;

class PixController extends Controller
{
    public function __construct(
        protected PixService $service,
        protected ContaService $contaService
    ) {}

    public function create()
    {
        Gate::authorize('create', Pix::class);

        $user = auth()->user();

        $conta = $this->contaService->find(
            $user->contaCliente?->id
        );

        return view('pix.create', compact('conta'));
    }

    public function store(PixRequest $request)
    {
        Gate::authorize('create', Pix::class);

        $dados = $request->validated();

        $this->service->realizarPix(
            auth()->user(),
            $dados['conta_destino_id'],
            $dados['valor'],
            $dados['descricao'] ?? null
        );

        return redirect()
            ->back()
            ->with('success', 'PIX realizado com sucesso.');
    }

    public function show(Pix $pix)
    {
        Gate::authorize('view', $pix);

        return view('pix.show', compact('pix'));
    }
}