<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Conta extends Model implements Auditable
{
    use AuditableTrait;
    use SoftDeletes;

    protected $fillable = [
        'cliente_id',
        'gerente_id',
        'saldo',
        'limite',
        'bloqueado'
    ];

    protected function casts(): array
    {
        return [
            'saldo' => 'decimal:2',
            'limite' => 'decimal:2',
            'bloqueado' => 'boolean',
        ];
    }

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

    public function solicitacoes()
    {
        return $this->hasMany('App\Models\Solicitacao');
    }

}