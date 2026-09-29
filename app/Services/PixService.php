<?php

namespace App\Services;

use App\Repositories\ContaRepository;
use App\Repositories\PixRepository;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;

class PixService extends BaseService
{
    public function __construct(
        protected PixRepository $repository,
        protected ContaRepository $contaRepository
    ) {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function realizarPix(
        User $user,
        int $contaOrigemId,
        int $contaDestinoId,
        float $valor
    ) {
        return DB::transaction(function () use (
            $user,
            $contaOrigemId,
            $contaDestinoId,
            $valor
        ) {

            if ($valor <= 0) {
                throw new Exception(
                    'O valor do PIX deve ser maior que zero.'
                );
            }

            if ($contaOrigemId === $contaDestinoId) {
                throw new Exception(
                    'A conta de origem e destino devem ser diferentes.'
                );
            }

            $contaOrigem = $this->contaRepository->find($contaOrigemId);
            $contaDestino = $this->contaRepository->find($contaDestinoId);

            if (!$contaOrigem) {
                throw new Exception(
                    'Conta de origem não encontrada.'
                );
            }

            if (!$contaDestino) {
                throw new Exception(
                    'Conta de destino não encontrada.'
                );
            }

            // Garante que o usuário autenticado é dono da conta de origem
            if ($contaOrigem->cliente_id !== $user->id) {
                throw new Exception(
                    'Você não pode realizar um PIX a partir desta conta.'
                );
            }

            if ($contaOrigem->bloqueado) {
                throw new Exception(
                    'A conta de origem está bloqueada.'
                );
            }

            if ($contaDestino->bloqueado) {
                throw new Exception(
                    'A conta de destino está bloqueada.'
                );
            }

            if ($contaOrigem->saldo < $valor) {
                throw new Exception(
                    'Saldo insuficiente.'
                );
            }

            $contaOrigem->saldo -= $valor;
            $contaOrigem->save();

            $contaDestino->saldo += $valor;
            $contaDestino->save();

            return $this->repository->store([
                'conta_origem_id' => $contaOrigemId,
                'conta_destino_id' => $contaDestinoId,
                'valor' => $valor,
            ]);
        });
    }
}