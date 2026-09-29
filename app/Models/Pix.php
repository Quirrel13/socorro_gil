<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Pix extends Model
{

    use AuditableTrait;

    protected $fillable = [
        'conta_origem_id',
        'conta_destino_id',
        'descricao',
        'valor'
    ];

    public function contaOrigem()
    {
        return $this->belongsTo('App\Models\Conta', 'conta_origem_id');
    }

    public function contaDestino()
    {
        return $this->belongsTo('App\Models\Conta', 'conta_destino_id');
    }

}
