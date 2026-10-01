<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PixResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'valor' => $this->valor,
            'descricao' => $this->descricao,
            'data' => $this->created_at,
            'origem' => $this->whenLoaded('contaOrigem', fn () => [
                'conta' => $this->conta_origem_id,
                'nome' => $this->contaOrigem->cliente?->name,
            ]),
            'destino' => $this->whenLoaded('contaDestino', fn () => [
                'conta' => $this->conta_destino_id,
                'nome' => $this->contaDestino->cliente?->name,
            ]),
        ];
    }
}