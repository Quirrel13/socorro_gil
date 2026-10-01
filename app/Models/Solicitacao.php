<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\StatusSolicitacao;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Solicitacao extends Model implements Auditable
{
    use AuditableTrait;

    protected $fillable = [
        'conta_id',
        'limite',
        'status',
        'solicitante_id',
        'avaliador_id',
        'motivo_recusa'
    ];

    public function conta()
    {
        return $this->belongsTo('App\Models\Conta');
    }

    /** Gerente de conta que fez a solicitação. */
    public function solicitante()
    {
        return $this->belongsTo('App\Models\User', 'solicitante_id');
    }

    /** Gerente geral que aprovou ou recusou. */
    public function avaliador()
    {
        return $this->belongsTo('App\Models\User', 'avaliador_id');
    }

    protected function casts(): array
    {
        return [
            'status' => StatusSolicitacao::class,
            'limite' => 'decimal:2',
        ];
    }
}