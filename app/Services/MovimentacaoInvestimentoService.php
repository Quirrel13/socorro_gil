<?php

namespace App\Services;

use App\Exceptions\RegraDeNegocioException;
use App\Repositories\ContaRepository;
use App\Repositories\InvestimentoRepository;
use App\Repositories\MovimentacaoInvestimentoRepository;
use App\Support\Dinheiro;
use Illuminate\Support\Facades\DB;

class MovimentacaoInvestimentoService extends BaseService
{
    public function __construct(
        protected MovimentacaoInvestimentoRepository $repository,
        protected InvestimentoRepository $investimentoRepository,
        protected ContaRepository $contaRepository
    ) {}

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function listarPorCliente(int $clienteId)
    {
        return $this->repository->listarPorCliente($clienteId);
    }

    public function aplicar(int $investimentoId, string|float $valor)
    {
        return $this->movimentar($investimentoId, $valor, true);
    }

    public function resgatar(int $investimentoId, string|float $valor)
    {
        return $this->movimentar($investimentoId, $valor, false);
    }

    protected function movimentar(int $investimentoId, string|float $valor, bool $aplicacao)
    {
        $centavos = Dinheiro::centavos($valor);

        if ($centavos <= 0) {
            throw new RegraDeNegocioException(
                $aplicacao
                    ? 'O valor da aplicação deve ser maior que zero.'
                    : 'O valor do resgate deve ser maior que zero.'
            );
        }

        return DB::transaction(function () use ($investimentoId, $centavos, $aplicacao) {

            $investimento = $this->investimentoRepository->buscarParaAtualizar($investimentoId);

            if (!$investimento) {
                throw new RegraDeNegocioException('Investimento não encontrado.');
            }

            $conta = $this->contaRepository->buscarParaAtualizar($investimento->conta_id);

            if (!$conta) {
                throw new RegraDeNegocioException('Conta do investimento não encontrada.');
            }

            if ($conta->bloqueado) {
                throw new RegraDeNegocioException(
                    $aplicacao
                        ? 'Não é possível realizar uma aplicação em uma conta bloqueada.'
                        : 'Não é possível realizar um resgate de uma conta bloqueada.'
                );
            }

            $saldo = Dinheiro::centavos($conta->saldo);
            $aplicado = Dinheiro::centavos($investimento->valor);

            if ($aplicacao) {
                if ($saldo < $centavos) {
                    throw new RegraDeNegocioException('Saldo insuficiente na conta.');
                }
                $saldo -= $centavos;
                $aplicado += $centavos;
            } else {
                if ($aplicado < $centavos) {
                    throw new RegraDeNegocioException('Saldo insuficiente no investimento.');
                }
                $saldo += $centavos;
                $aplicado -= $centavos;
            }

            $this->contaRepository->update(
                ['saldo' => Dinheiro::formatar($saldo)],
                $conta->id
            );

            $this->investimentoRepository->update(
                ['valor' => Dinheiro::formatar($aplicado)],
                $investimentoId
            );

            return $this->repository->store([
                'investimento_id' => $investimentoId,
                'valor' => Dinheiro::formatar($aplicacao ? $centavos : -$centavos),
            ]);
        });
    }
}