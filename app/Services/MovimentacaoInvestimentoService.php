<?php

namespace App\Services;

use App\Repositories\MovimentacaoInvestimentoRepository;
use App\Repositories\InvestimentoRepository;
use Illuminate\Support\Facades\DB;
use Exception;

class MovimentacaoInvestimentoService extends BaseService
{
    public function __construct(
        protected MovimentacaoInvestimentoRepository $repository,
        protected InvestimentoRepository $investimentoRepository
    ) {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function aplicar(
        int $investimentoId,
        float $valor
    ) {
        if ($valor <= 0) {
            throw new Exception(
                'O valor da aplicação deve ser maior que zero.'
            );
        }

        return DB::transaction(function () use (
            $investimentoId,
            $valor
        ) {
            $investimento = $this->investimentoRepository->find(
                $investimentoId
            );

            if (!$investimento) {
                throw new Exception(
                    'Investimento não encontrado.'
                );
            }

            $this->investimentoRepository->update([
                'valor' => $investimento->valor + $valor
            ], $investimentoId);

            return $this->repository->store([
                'investimento_id' => $investimentoId,
                'valor' => $valor
            ]);
        });
    }

    public function resgatar(
        int $investimentoId,
        float $valor
    ) {
        if ($valor <= 0) {
            throw new Exception(
                'O valor do resgate deve ser maior que zero.'
            );
        }

        return DB::transaction(function () use (
            $investimentoId,
            $valor
        ) {
            $investimento = $this->investimentoRepository->find(
                $investimentoId
            );

            if (!$investimento) {
                throw new Exception(
                    'Investimento não encontrado.'
                );
            }

            if ($investimento->valor < $valor) {
                throw new Exception(
                    'Saldo insuficiente no investimento.'
                );
            }

            $this->investimentoRepository->update([
                'valor' => $investimento->valor - $valor
            ], $investimentoId);

            return $this->repository->store([
                'investimento_id' => $investimentoId,
                'valor' => -$valor
            ]);
        });
    }
}