<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoInvestimento extends Model
{
    /**
     * Convenção do campo "valor" (perspectiva do INVESTIMENTO):
     *   valor > 0  => aplicação (dinheiro SAIU da conta)
     *   valor < 0  => resgate   (dinheiro ENTROU na conta)
     */
    protected $fillable = [
        'investimento_id',
        'valor'
    ];

    protected function casts(): array
    {
        return ['valor' => 'decimal:2'];
    }

    public function investimento()
    {
        return $this->belongsTo('App\Models\Investimento');
    }
}