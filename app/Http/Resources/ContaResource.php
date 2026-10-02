<?php

namespace App\Http\Resources;

use App\Support\Dinheiro;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'numero' => $this->id,
            'saldo' => $this->saldo,
            'limite' => $this->limite,
            // limite funciona como cheque especial: disponível = saldo + limite
            'saldo_disponivel' => Dinheiro::formatar(
                Dinheiro::centavos($this->saldo) + Dinheiro::centavos($this->limite)
            ),
            'bloqueado' => $this->bloqueado,
            'investimentos' => $this->when(! $this->bloqueado, fn () => InvestimentoResource::collection($this->whenLoaded('investimentos'))),
            'total_investido' => $this->when(
                ! $this->bloqueado && $this->relationLoaded('investimentos'),
                fn () => Dinheiro::formatar($this->investimentos->sum(fn ($i) => Dinheiro::centavos($i->valor)))
            ),
        ];
    }
}