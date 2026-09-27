<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimentacaoInvestimento extends Model
{
    protected $fillable = [
        'investimento_id',
        'conta_id',
        'valor'
    ];

    public function investimento()
    {
        return $this->belongsTo('App\Models\Investimento');
    }

    public function conta()
    {
        return $this->belongsTo('App\Models\Conta');
    }

}
