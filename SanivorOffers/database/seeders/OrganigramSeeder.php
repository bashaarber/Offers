<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganigramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organigrams = [
            ['name' => 'Bürogebäude', 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Wohngebäude', 'isSelected0' => 1, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Industriegebäude', 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 1, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Gewerbegebäude', 'isSelected0' => 1, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 1, 'isSelected4' => 0],
            ['name' => 'Sanierung', 'isSelected0' => 0, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
        ];

        DB::table('organigrams')->insert($organigrams);
    }
}
