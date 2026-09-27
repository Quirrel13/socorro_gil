<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoInvestimento;

class TipoInvestimentoSeeder extends Seeder
{
    public function run(): void
    {
        TipoInvestimento::create([
            'nome' => 'CDB',
        ]);

        TipoInvestimento::create([
            'nome' => 'Poupança',
        ]);

        TipoInvestimento::create([
            'nome' => 'CDI',
        ]);
    }
}