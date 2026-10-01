<?php

namespace App\Services;

use App\Enums\StatusSolicitacao;
use App\Exceptions\RegraDeNegocioException;
use App\Repositories\ContaRepository;
use App\Repositories\SolicitacaoRepository;
use App\Support\Dinheiro;
use Illuminate\Support\Facades\DB;

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

    public function solicitar(int $contaId, string|float $limite, int $solicitanteId)
    {
        $limiteCentavos = Dinheiro::centavos($limite);

        if ($limiteCentavos <= 0) {
            throw new RegraDeNegocioException('O limite solicitado deve ser maior que zero.');
        }

        $conta = $this->contaRepository->find($contaId);

        if (!$conta) {
            throw new RegraDeNegocioException('Conta não encontrada.');
        }

        if ((int) $conta->gerente_id !== $solicitanteId) {
            throw new RegraDeNegocioException('Você não é o gerente responsável por esta conta.');
        }

        if ($conta->bloqueado) {
            throw new RegraDeNegocioException(
                'Não é possível solicitar aumento para uma conta bloqueada.'
            );
        }

        if ($limiteCentavos <= Dinheiro::centavos($conta->limite)) {
            throw new RegraDeNegocioException(
                'O limite solicitado deve ser maior que o limite atual da conta.'
            );
        }

        if ($this->repository->existePendente($contaId)) {
            throw new RegraDeNegocioException(
                'Já existe uma solicitação pendente para esta conta.'
            );
        }

        return $this->repository->store([
            'conta_id' => $contaId,
            'limite' => Dinheiro::formatar($limiteCentavos),
            'status' => StatusSolicitacao::PENDENTE,
            'solicitante_id' => $solicitanteId,
            'avaliador_id' => null,
            'motivo_recusa' => null,
        ]);
    }

    public function listarTodas()
    {
        return $this->repository->listarTodas();
    }

    public function listarPorGerente(int $gerenteId)
    {
        return $this->repository->listarPorGerente($gerenteId);
    }

    public function aprovar(int $solicitacaoId, int $avaliadorId)
    {
        return DB::transaction(function () use ($solicitacaoId, $avaliadorId) {
            $solicitacao = $this->repository->buscarParaAtualizar($solicitacaoId);

            if (!$solicitacao) {
                throw new RegraDeNegocioException('Solicitação não encontrada.');
            }

            if ($solicitacao->status !== StatusSolicitacao::PENDENTE) {
                throw new RegraDeNegocioException(
                    'Apenas solicitações pendentes podem ser aprovadas.'
                );
            }

            $conta = $this->contaRepository->buscarParaAtualizar($solicitacao->conta_id);

            if (!$conta) {
                throw new RegraDeNegocioException('Conta da solicitação não encontrada.');
            }

            if ($conta->bloqueado) {
                throw new RegraDeNegocioException(
                    'Não é possível aumentar o limite de uma conta bloqueada.'
                );
            }

            $this->contaRepository->update(['limite' => $solicitacao->limite], $conta->id);

            return $this->repository->update([
                'status' => StatusSolicitacao::APROVADA,
                'avaliador_id' => $avaliadorId,
                'motivo_recusa' => null,
            ], $solicitacaoId);
        });
    }

    public function recusar(int $solicitacaoId, string $motivo, int $avaliadorId)
    {
        if (trim($motivo) === '') {
            throw new RegraDeNegocioException('É necessário informar o motivo da recusa.');
        }

        return DB::transaction(function () use ($solicitacaoId, $motivo, $avaliadorId) {
            $solicitacao = $this->repository->buscarParaAtualizar($solicitacaoId);

            if (!$solicitacao) {
                throw new RegraDeNegocioException('Solicitação não encontrada.');
            }

            if ($solicitacao->status !== StatusSolicitacao::PENDENTE) {
                throw new RegraDeNegocioException(
                    'Apenas solicitações pendentes podem ser recusadas.'
                );
            }

            return $this->repository->update([
                'status' => StatusSolicitacao::RECUSADA,
                'motivo_recusa' => $motivo,
                'avaliador_id' => $avaliadorId,
            ], $solicitacaoId);
        });
    }
    
    public function contarPendentes(?int $solicitanteId = null): int
    {
        return $this->repository->contarPendentes($solicitanteId);
    }
}