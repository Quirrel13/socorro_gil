<?php

namespace App\Http\Resources;

use App\Support\Dinheiro;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovimentacaoInvestimentoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $centavos = Dinheiro::centavos($this->valor);

        return [
            'id' => $this->id,
            'investimento_id' => $this->investimento_id,
            'investimento' => $this->whenLoaded('investimento', fn () => $this->investimento->tipo?->nome),
            // no banco: aplicação > 0, resgate < 0
            'tipo' => $centavos > 0 ? 'aplicacao' : 'resgate',
            'valor' => Dinheiro::formatar(abs($centavos)),
            'data' => $this->created_at,
        ];
    }
}