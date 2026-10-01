<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
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
        $user = auth()->user();

        if ($user->role->name === 'gerente_geral') {
            $usuarios = $this->service->listarGerentesDeConta();
        } elseif ($user->role->name === 'gerente_conta') {
            $usuarios = $this->service->listarClientesPorGerente($user->id);
        } else {
            abort(403);
        }

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

        $user = auth()->user();

        $dados = $request->validated();

        if ($user->role->name === 'gerente_geral') {
            $role = Role::where('name', 'gerente_conta')->firstOrFail();
        } else {
            $role = Role::where('name', 'cliente')->firstOrFail();
        }

        $dados['role_id'] = $role->id;

        $this->service->store($dados);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function show(User $user)
    {
        Gate::authorize('view', $user);

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

        $this->service->update(
            $request->validated(),
            $user->id
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $this->service->remove($user->id);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuário excluído com sucesso.');
    }
}