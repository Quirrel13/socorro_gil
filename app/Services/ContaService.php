<?php

namespace App\Services;

use App\Repositories\ContaRepository;
use App\Repositories\InvestimentoRepository;
use App\Repositories\TipoInvestimentoRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class ContaService extends BaseService
{
    public function __construct(
        protected ContaRepository $repository,
        protected InvestimentoRepository $investimentoRepository,
        protected TipoInvestimentoRepository $tipoInvestimentoRepository
    ) {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function criarConta(array $data)
    {
        return DB::transaction(function () use ($data) {

            $conta = $this->repository->store($data);

            $tipos = $this->tipoInvestimentoRepository->list();

            foreach ($tipos as $tipo) {
                $this->investimentoRepository->store([
                    'conta_id' => $conta->id,
                    'tipo_investimento_id' => $tipo->id,
                    'valor' => 0,
                ]);
            }

            return $conta;
        });
    }

    public function bloquear(int $contaId)
    {
        $conta = $this->repository->find($contaId);

        if (!$conta) {
            throw new Exception('Conta não encontrada.');
        }

        if ($conta->bloqueado) {
            throw new Exception('A conta já está bloqueada.');
        }

        return $this->repository->update([
            'bloqueado' => true
        ], $contaId);
    }

    public function desbloquear(int $contaId)
    {
        $conta = $this->repository->find($contaId);

        if (!$conta) {
            throw new Exception('Conta não encontrada.');
        }

        if (!$conta->bloqueado) {
            throw new Exception('A conta já está desbloqueada.');
        }

        return $this->repository->update([
            'bloqueado' => false
        ], $contaId);
    }
}