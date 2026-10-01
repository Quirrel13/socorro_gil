<?php

namespace App\Services;

use App\Exceptions\RegraDeNegocioException;
use App\Models\User;
use App\Repositories\ContaRepository;
use App\Repositories\PixRepository;
use App\Support\Dinheiro;
use Illuminate\Support\Facades\DB;

class PixService extends BaseService
{
    public function __construct(
        protected PixRepository $repository,
        protected ContaRepository $contaRepository
    ) {}

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function realizarPix(
        User $user,
        int $contaDestinoId,
        string|float $valor,
        ?string $descricao = null
    ) {
        $valorCentavos = Dinheiro::centavos($valor);

        if ($valorCentavos <= 0) {
            throw new RegraDeNegocioException('O valor do PIX deve ser maior que zero.');
        }

        return DB::transaction(function () use ($user, $contaDestinoId, $valorCentavos, $descricao) {

            $contaOrigem = $this->contaRepository->buscarPorCliente($user->id);

            if (!$contaOrigem) {
                throw new RegraDeNegocioException('Conta de origem não encontrada.');
            }

            if ($contaOrigem->id === $contaDestinoId) {
                throw new RegraDeNegocioException('A conta de origem e destino devem ser diferentes.');
            }

            $ids = [$contaOrigem->id, $contaDestinoId];
            sort($ids);

            $travadas = [];
            foreach ($ids as $id) {
                $travadas[$id] = $this->contaRepository->buscarParaAtualizar($id);
            }

            $origem = $travadas[$contaOrigem->id];
            $destino = $travadas[$contaDestinoId];

            if (!$destino) {
                throw new RegraDeNegocioException('Conta de destino não encontrada.');
            }

            if ($origem->bloqueado) {
                throw new RegraDeNegocioException('A conta de origem está bloqueada.');
            }

            if ($destino->bloqueado) {
                throw new RegraDeNegocioException('A conta de destino está bloqueada.');
            }

            $saldoOrigem = Dinheiro::centavos($origem->saldo);
            $limiteOrigem = Dinheiro::centavos($origem->limite);

            if ($saldoOrigem + $limiteOrigem < $valorCentavos) {
                throw new RegraDeNegocioException('Saldo insuficiente.');
            }

            $this->contaRepository->update([
                'saldo' => Dinheiro::formatar($saldoOrigem - $valorCentavos),
            ], $origem->id);

            $this->contaRepository->update([
                'saldo' => Dinheiro::formatar(Dinheiro::centavos($destino->saldo) + $valorCentavos),
            ], $destino->id);

            return $this->repository->store([
                'conta_origem_id' => $origem->id,
                'conta_destino_id' => $destino->id,
                'valor' => Dinheiro::formatar($valorCentavos),
                'descricao' => ($descricao !== null && trim($descricao) !== '')
                    ? $descricao
                    : 'Nenhum comentário',
            ]);
        });
    }
}