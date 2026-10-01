// Investimento.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investimento extends Model
{
    protected $fillable = [
        'conta_id',
        'tipo_investimento_id',
        'valor'
    ];

    protected function casts(): array
    {
        return ['valor' => 'decimal:2'];
    }

    public function conta()
    {
        return $this->belongsTo('App\Models\Conta');
    }

    public function tipo()
    {
        return $this->belongsTo('App\Models\TipoInvestimento', 'tipo_investimento_id');
    }
}