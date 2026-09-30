<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\StatusSolicitacao;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Solicitacao extends Model
{

    use AuditableTrait;

    protected $fillable = [
        'conta_id',
        'limite',
        'status',
        'gerente_id',
        'motivo_recusa'
    ];

    public function conta()
    {
        return $this->belongsTo('App\Models\Conta');
    }

    public function gerente()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function casts(): array
    {
        return [
            'status' => StatusSolicitacao::class,
        ];
    }

}
