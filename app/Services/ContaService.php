<?php

namespace App\Services;

use App\Exceptions\RegraDeNegocioException;
use App\Repositories\ContaRepository;
use App\Repositories\InvestimentoRepository;
use App\Repositories\TipoInvestimentoRepository;
use App\Support\Dinheiro;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ContaService extends BaseService
{
    public function __construct(
        protected ContaRepository $repository,
        protected InvestimentoRepository $investimentoRepository,
        protected TipoInvestimentoRepository $tipoInvestimentoRepository,
        protected UserService $userService
    ) {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function abrirConta(array $dados, int $gerenteId)
    {
        return DB::transaction(function () use ($dados, $gerenteId) {

            $cliente = $this->userService->criarCliente([
                'name' => $dados['name'],
                'email' => $dados['email'],
                'password' => $dados['password'],
            ]);

            $conta = $this->repository->store([
                'cliente_id' => $cliente->id,
                'gerente_id' => $gerenteId,
                'saldo' => $dados['saldo'],
                'limite' => $dados['limite'],
                'bloqueado' => false,
            ]);

            foreach ($this->tipoInvestimentoRepository->list() as $tipo) {
                $this->investimentoRepository->store([
                    'conta_id' => $conta->id,
                    'tipo_investimento_id' => $tipo->id,
                    'valor' => 0,
                ]);
            }

            return $conta;
        });
    }

    public function atualizarDadosCliente(int $contaId, array $dados)
    {
        return DB::transaction(function () use ($contaId, $dados) {
            $conta = $this->repository->find($contaId);

            if (!$conta) {
                throw new RegraDeNegocioException('Conta não encontrada.');
            }

            $this->userService->update(
                Arr::only($dados, ['name', 'email', 'password']),
                $conta->cliente_id
            );

            return $this->repository->find($contaId, ['cliente']);
        });
    }

    public function remover(int $contaId)
    {
        return DB::transaction(function () use ($contaId) {
            $conta = $this->repository->buscarParaAtualizar($contaId);

            if (!$conta) {
                throw new RegraDeNegocioException('Conta não encontrada.');
            }

            if (Dinheiro::centavos($conta->saldo) !== 0) {
                throw new RegraDeNegocioException(
                    'Não é possível remover uma conta com saldo diferente de zero.'
                );
            }

            $investido = $this->investimentoRepository
                ->listarPorConta($conta->id)
                ->sum(fn ($i) => Dinheiro::centavos($i->valor));

            if ($investido !== 0) {
                throw new RegraDeNegocioException(
                    'Não é possível remover uma conta com valores aplicados.'
                );
            }

            $this->repository->remove($conta->id);
            $this->userService->remove($conta->cliente_id);

            return true;
        });
    }

    public function bloquear(int $contaId)
    {
        $conta = $this->repository->find($contaId);

        if (!$conta) {
            throw new RegraDeNegocioException('Conta não encontrada.');
        }

        if ($conta->bloqueado) {
            throw new RegraDeNegocioException('A conta já está bloqueada.');
        }

        return $this->repository->update(['bloqueado' => true], $contaId);
    }

    public function desbloquear(int $contaId)
    {
        $conta = $this->repository->find($contaId);

        if (!$conta) {
            throw new RegraDeNegocioException('Conta não encontrada.');
        }

        if (!$conta->bloqueado) {
            throw new RegraDeNegocioException('A conta já está desbloqueada.');
        }

        return $this->repository->update(['bloqueado' => false], $contaId);
    }

    public function listarPorGerente(int $gerenteId)
    {
        return $this->repository->listarPorGerente($gerenteId);
    }

    public function contaDoCliente(int $clienteId)
    {
        $conta = $this->repository->buscarPorCliente($clienteId, ['investimentos.tipo']);

        if (!$conta) {
            throw new RegraDeNegocioException('Conta não encontrada.');
        }

        return $conta;
    }
}