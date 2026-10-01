<?php

namespace App\Services;

use App\Repositories\MovimentacaoInvestimentoRepository;
use App\Repositories\PixRepository;
use App\Support\Dinheiro;
use Illuminate\Support\Collection;

class ExtratoService
{
    public function __construct(
        protected PixRepository $pixRepository,
        protected MovimentacaoInvestimentoRepository $movimentacaoRepository
    ) {
    }

    public function gerar(int $contaId, ?string $inicio = null, ?string $fim = null): Collection
    {
        $pix = $this->pixRepository->listarPorConta($contaId, $inicio, $fim)
            ->map(function ($item) use ($contaId) {
                $enviado = (int) $item->conta_origem_id === $contaId;
                $centavos = Dinheiro::centavos($item->valor);
                $outra = $enviado ? $item->contaDestino : $item->contaOrigem;
                $nome = $outra?->cliente?->name ?? 'outra conta';

                return [
                    'data' => $item->created_at,
                    'tipo' => $enviado ? 'pix_enviado' : 'pix_recebido',
                    'descricao' => ($enviado ? 'Pix enviado para ' : 'Pix recebido de ') . $nome,
                    'valor' => Dinheiro::formatar($enviado ? -$centavos : $centavos),
                    'sentido' => $enviado ? 'saida' : 'entrada',
                ];
            });

        $investimentos = $this->movimentacaoRepository->listarPorConta($contaId, $inicio, $fim)
            ->map(function ($mov) {
                $centavos = Dinheiro::centavos($mov->valor);
                $aplicacao = $centavos > 0;   // no banco: aplicação > 0, resgate < 0
                $tipo = $mov->investimento?->tipo?->nome ?? 'investimento';

                return [
                    'data' => $mov->created_at,
                    'tipo' => $aplicacao ? 'aplicacao' : 'resgate',
                    'descricao' => ($aplicacao ? 'Aplicação em ' : 'Resgate de ') . $tipo,
                    // sinal invertido: do ponto de vista da CONTA
                    'valor' => Dinheiro::formatar(-$centavos),
                    'sentido' => $aplicacao ? 'saida' : 'entrada',
                ];
            });

        return $pix->concat($investimentos)
            ->sortByDesc(fn ($linha) => $linha['data']->getTimestamp())
            ->values();
    }
}