<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conta extends Model
{
    
    protected $fillable = [
        'cliente_id',
        'gerente_id',
        'saldo',
        'limite',
        'bloqueado'
    ];

    public function cliente()
    {
        return $this->belongsTo('App\Models\User', 'cliente_id');
    }

    public function gerente()
    {
        return $this->belongsTo('App\Models\User', 'gerente_id');
    }

    public function investimentos()
    {
        return $this->hasMany('App\Models\Investimento');
    }

    public function pixEnviados()
    {
        return $this->hasMany('App\Models\Pix', 'conta_origem_id');
    }

    public function pixRecebidos()
    {
        return $this->hasMany('App\Models\Pix', 'conta_destino_id');
    }

    public function movimentacoesInvestimentos()
    {
        return $this->hasMany('App\Models\MovimentacaoInvestimento');
    }

}
