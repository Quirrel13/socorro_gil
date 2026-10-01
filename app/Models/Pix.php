// Pix.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pix extends Model
{
    protected $fillable = [
        'conta_origem_id',
        'conta_destino_id',
        'descricao',
        'valor'
    ];

    protected function casts(): array
    {
        return ['valor' => 'decimal:2'];
    }

    public function contaOrigem()
    {
        return $this->belongsTo('App\Models\Conta', 'conta_origem_id');
    }

    public function contaDestino()
    {
        return $this->belongsTo('App\Models\Conta', 'conta_destino_id');
    }
}