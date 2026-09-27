<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["name" => "gerente_geral"],
            ["name" => "gerente_conta"],
            ["name" => "cliente"]
        ];
        DB::table('roles')->insert($data);
    }
}
