<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function __construct(
        protected UserService $service
    ) {
    }

    public function index()
    {
        Gate::authorize('viewAny', User::class);

        $usuarios = $this->service->listarGerentesDeConta();

        return view('users.index', compact('usuarios'));
    }

    public function create()
    {
        Gate::authorize('create', User::class);

        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        Gate::authorize('create', User::class);

        $this->service->criarGerenteConta($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', 'Gerente de conta criado com sucesso.');
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);

        $user->load('contaGerente.cliente');

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        Gate::authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $this->service->update($request->validated(), $user->id);

        return redirect()
            ->route('users.index')
            ->with('success', 'Gerente de conta atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $this->service->remove($user->id);

        return redirect()
            ->route('users.index')
            ->with('success', 'Gerente de conta removido com sucesso.');
    }
}