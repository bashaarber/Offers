<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupElementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupElements = [
            ['name' => 'Standard Wände', 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Brandschutz Wände', 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 1],
            ['name' => 'Schallschutz Wände', 'isSelected0' => 1, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'DeBO System Wände', 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 1, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Freistehende Konstruktionen', 'isSelected0' => 0, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Wände mit Öffnungen', 'isSelected0' => 1, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 1, 'isSelected4' => 0],
        ];

        DB::table('group_elements')->insert($groupElements);
    }
}
