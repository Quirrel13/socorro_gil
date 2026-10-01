<?php

namespace App\Services;

use App\Enums\NomeRole;
use App\Exceptions\RegraDeNegocioException;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;

class UserService extends BaseService
{
    public function __construct(
        protected UserRepository $repository,
        protected RoleRepository $roleRepository
    ) {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function listarGerentesDeConta()
    {
        return $this->repository->listarGerentesDeConta();
    }

    public function listarClientes()
    {
        return $this->repository->listarClientes();
    }

    public function listarClientesPorGerente(int $gerenteId)
    {
        return $this->repository->listarClientesPorGerente($gerenteId);
    }

    public function listarClientesComContaBloqueada()
    {
        return $this->repository->listarClientesComContaBloqueada();
    }

    public function criarGerenteConta(array $dados)
    {
        return $this->criarComRole($dados, NomeRole::GERENTE_CONTA);
    }

    public function criarCliente(array $dados)
    {
        return $this->criarComRole($dados, NomeRole::CLIENTE);
    }

    protected function criarComRole(array $dados, NomeRole $nomeRole)
    {
        $role = $this->roleRepository->buscarPorNome($nomeRole);

        if (!$role) {
            throw new RegraDeNegocioException('Perfil de acesso não encontrado.');
        }

        // TODO (Mailtrap): enviar e-mail com login e senha aqui.
        // A senha em texto puro só existe neste ponto ($dados['password']);
        // o model faz o hash ao salvar.

        return $this->repository->store([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => $dados['password'],
            'role_id' => $role->id,
        ]);
    }

    public function update(array $data, int|string $id)
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return parent::update($data, $id);
    }

    public function remove(int|string $id)
    {
        $user = $this->repository->find($id, ['role']);

        if (!$user) {
            throw new RegraDeNegocioException('Usuário não encontrado.');
        }

        if (
            $user->temRole(NomeRole::GERENTE_CONTA)
            && $this->repository->contarContasDoGerente($user->id) > 0
        ) {
            throw new RegraDeNegocioException(
                'Este gerente ainda possui contas vinculadas e não pode ser removido.'
            );
        }

        return parent::remove($id);
    }
}