<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganigramSeeder extends Seeder
{
    public function run(): void
    {
        $organigrams = [
            ['name' => 'Rahme'],
            ['name' => 'Installationsmodule'],
            ['name' => 'Verrohrung'],
            ['name' => 'PE-Vorfabrikation'],
            ['name' => 'Kanslaisation'],
        ];

        DB::table('organigrams')->insert($organigrams);
    }
}
