<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\StatusSolicitacao;

class Solicitacao extends Model
{

    protected $fillable = [
        'conta_id',
        'limite',
        'status',
        'gerente_id',
        'motivo_recusa'
    ];

    protected function conta()
    {
        return $this->belongsTo('App\Models\Conta');
    }

    protected function gerente()
    {
        return $this->belongsTo('App\Models\User');
    }

    protected function casts(): array
    {
        return [
            'status' => StatusSolicitacao::class,
        ];
    }

}
