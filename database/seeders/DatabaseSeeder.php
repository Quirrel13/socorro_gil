<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(ResourceSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TipoInvestimentoSeeder::class);
        $this->call(ContaSeeder::class);
        $this->call(InvestimentoSeeder::class);
        $this->call(PixSeeder::class);
        $this->call(MovimentacaoInvestimentoSeeder::class);
    }
}
