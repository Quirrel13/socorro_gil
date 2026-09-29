<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class MovimentacaoInvestimento extends Model
{

    use AuditableTrait;

    protected $fillable = [
        'investimento_id',
        'valor'
    ];

    public function investimento()
    {
        return $this->belongsTo('App\Models\Investimento');
    }

}
