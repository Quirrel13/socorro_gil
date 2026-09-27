<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{

    protected $fillable = [
        'conta_id',
        'tipo_id',
        'valor'
    ];

    public function conta()
    {
        return $this->belongsTo('App\Models\Conta');
    }

    public function tipo()
    {
        return $this->belongsTo('App\Models\TipoInvestimento');
    }

}
