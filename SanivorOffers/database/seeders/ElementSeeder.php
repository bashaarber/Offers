<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elements = [
            ['name' => 'Wandkonstruktion Standard', 'quantity' => 1, 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Trennwand Raumhoch', 'quantity' => 1, 'isSelected0' => 1, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Vorwand DeBO-System', 'quantity' => 1, 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 1, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Freistehende Wand', 'quantity' => 1, 'isSelected0' => 0, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Wand mit Türöffnung', 'quantity' => 1, 'isSelected0' => 1, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 1, 'isSelected4' => 0],
            ['name' => 'Brandschutzwand', 'quantity' => 1, 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 1],
            ['name' => 'Schallschutzwand', 'quantity' => 1, 'isSelected0' => 1, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Wand mit Fensteröffnung', 'quantity' => 1, 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 0, 'isSelected3' => 1, 'isSelected4' => 0],
            ['name' => 'Trennwand Teilhoch', 'quantity' => 1, 'isSelected0' => 0, 'isSelected1' => 1, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
            ['name' => 'Vorwand Raumhoch', 'quantity' => 1, 'isSelected0' => 1, 'isSelected1' => 0, 'isSelected2' => 0, 'isSelected3' => 0, 'isSelected4' => 0],
        ];

        DB::table('elements')->insert($elements);
    }
}
