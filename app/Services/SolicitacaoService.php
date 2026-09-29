<?php

namespace App\Services;

use App\Repositories\SolicitacaoRepository;
use App\Repositories\ContaRepository;
use App\Enums\StatusSolicitacao;
use Illuminate\Support\Facades\DB;
use Exception;

class SolicitacaoService extends BaseService
{
    public function __construct(
        protected SolicitacaoRepository $repository,
        protected ContaRepository $contaRepository
    ) {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function solicitar(
        int $contaId,
        float $limite
    ) {
        if ($limite <= 0) {
            throw new Exception('O limite solicitado deve ser maior que zero.');
        }

        $conta = $this->contaRepository->find($contaId);

        if (!$conta) {
            throw new Exception('Conta não encontrada.');
        }

        if ($conta->gerente_id !== auth()->id()) {
            throw new Exception('Você não é o gerente responsável por esta conta.');
        }

        if ($conta->bloqueado) {
            throw new Exception(
                'Não é possível solicitar aumento para uma conta bloqueada.'
            );
        }

        return $this->repository->store([
            'conta_id' => $contaId,
            'limite' => $limite,
            'status' => StatusSolicitacao::PENDENTE,
            'gerente_id' => null,
            'motivo_recusa' => null,
        ]);
    }

    public function aprovar(
        int $solicitacaoId,
        int $gerenteId
    ) {
        return DB::transaction(function () use (
            $solicitacaoId,
            $gerenteId
        ) {
            $solicitacao = $this->repository->find($solicitacaoId);

            if (!$solicitacao) {
                throw new Exception('Solicitação não encontrada.');
            }

            if ($solicitacao->status !== StatusSolicitacao::PENDENTE) {
                throw new Exception(
                    'Apenas solicitações pendentes podem ser aprovadas.'
                );
            }

            $conta = $this->contaRepository->find($solicitacao->conta_id);

            if (!$conta) {
                throw new Exception('Conta da solicitação não encontrada.');
            }

            if ($conta->bloqueado) {
                throw new Exception(
                    'Não é possível aumentar o limite de uma conta bloqueada.'
                );
            }

            $this->contaRepository->update([
                'limite' => $solicitacao->limite
            ], $conta->id);

            return $this->repository->update([
                'status' => StatusSolicitacao::APROVADA,
                'gerente_id' => $gerenteId,
                'motivo_recusa' => null,
            ], $solicitacaoId);
        });
    }

    public function recusar(
        int $solicitacaoId,
        string $motivo
    ) {
        if (empty(trim($motivo))) {
            throw new Exception('É necessário informar o motivo da recusa.');
        }

        $solicitacao = $this->repository->find($solicitacaoId);

        if (!$solicitacao) {
            throw new Exception('Solicitação não encontrada.');
        }

        if ($solicitacao->status !== StatusSolicitacao::PENDENTE) {
            throw new Exception(
                'Apenas solicitações pendentes podem ser recusadas.'
            );
        }

        return $this->repository->update([
            'status' => StatusSolicitacao::RECUSADA,
            'motivo_recusa' => $motivo,
            'gerente_id' => null,
        ], $solicitacaoId);
    }
}