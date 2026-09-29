<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Investimento extends Model
{

    use AuditableTrait;

    protected $fillable = [
        'conta_id',
        'tipo_investimento_id',
        'valor'
    ];

    public function conta()
    {
        return $this->belongsTo('App\Models\Conta');
    }

    public function tipo()
    {
        return $this->belongsTo('App\Models\TipoInvestimento', 'tipo_investimento_id');
    }

}
