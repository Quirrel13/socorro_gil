<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\StatusSolicitacao;

class Solicitacao extends Model
{

    protected $fillable = [
        'conta_id',
        'limite',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusSolicitacao::class,
        ];
    }

}
