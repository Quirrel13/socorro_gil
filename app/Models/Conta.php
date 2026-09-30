<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Conta extends Model
{

    use AuditableTrait;
    
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
        return $this->hasManyThrough(
            'App\Models\MovimentacaoInvestimento',
            'App\Models\Investimento',
            'conta_id',
            'investimento_id'
        );
    }

}
