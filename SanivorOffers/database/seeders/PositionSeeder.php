<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            [
                'description' => 'Vorwand Raumhoch 100x50mm',
                'description2' => 'Standardausführung mit Gipskarton',
                'blocktype' => 'Vorwand-Raumhoch',
                'b' => '100',
                'h' => '250',
                't' => '75',
                'quantity' => 10,
                'price_brutto' => 1250.00,
                'price_discount' => 1125.00,
                'discount' => 10.0,
                'material_brutto' => 450.00,
                'zeit_brutto' => 800.00,
                'material_costo' => 350.00,
                'material_profit' => 100.00,
                'ziet_costo' => 600.00,
                'ziet_profit' => 200.00,
                'costo_total' => 950.00,
                'profit_total' => 300.00,
                'position_number' => 1,
            ],
            [
                'description' => 'Trennwand DeBO-System',
                'description2' => 'Brandschutzausführung',
                'blocktype' => 'Vorwand DeBO-System',
                'b' => '150',
                'h' => '300',
                't' => '100',
                'quantity' => 5,
                'price_brutto' => 1875.00,
                'price_discount' => 1687.50,
                'discount' => 10.0,
                'material_brutto' => 750.00,
                'zeit_brutto' => 1125.00,
                'material_costo' => 600.00,
                'material_profit' => 150.00,
                'ziet_costo' => 850.00,
                'ziet_profit' => 275.00,
                'costo_total' => 1450.00,
                'profit_total' => 425.00,
                'position_number' => 2,
            ],
            [
                'description' => 'Freistehende Wand Raumhoch',
                'description2' => 'Schallschutzausführung',
                'blocktype' => 'Freistehend-Raumhoch',
                'b' => '120',
                'h' => '280',
                't' => '90',
                'quantity' => 8,
                'price_brutto' => 1680.00,
                'price_discount' => 1512.00,
                'discount' => 10.0,
                'material_brutto' => 640.00,
                'zeit_brutto' => 1040.00,
                'material_costo' => 500.00,
                'material_profit' => 140.00,
                'ziet_costo' => 780.00,
                'ziet_profit' => 260.00,
                'costo_total' => 1280.00,
                'profit_total' => 400.00,
                'position_number' => 3,
            ],
            [
                'description' => 'Vorwand Teilhoch',
                'description2' => 'Standardausführung',
                'blocktype' => 'Vorwand-Teilhoch',
                'b' => '100',
                'h' => '200',
                't' => '75',
                'quantity' => 12,
                'price_brutto' => 1080.00,
                'price_discount' => 972.00,
                'discount' => 10.0,
                'material_brutto' => 360.00,
                'zeit_brutto' => 720.00,
                'material_costo' => 280.00,
                'material_profit' => 80.00,
                'ziet_costo' => 540.00,
                'ziet_profit' => 180.00,
                'costo_total' => 820.00,
                'profit_total' => 260.00,
                'position_number' => 4,
            ],
            [
                'description' => 'Trennwand Raumhoch und Teilhoch',
                'description2' => 'Kombinierte Ausführung',
                'blocktype' => 'Vorwand-Raumhoch und Teilhoch',
                'b' => '110',
                'h' => '250',
                't' => '80',
                'quantity' => 6,
                'price_brutto' => 990.00,
                'price_discount' => 891.00,
                'discount' => 10.0,
                'material_brutto' => 360.00,
                'zeit_brutto' => 630.00,
                'material_costo' => 280.00,
                'material_profit' => 80.00,
                'ziet_costo' => 470.00,
                'ziet_profit' => 160.00,
                'costo_total' => 750.00,
                'profit_total' => 240.00,
                'position_number' => 5,
            ],
        ];

        DB::table('positions')->insert($positions);
    }
}
