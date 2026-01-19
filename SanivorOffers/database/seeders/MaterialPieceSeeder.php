<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaterialPieceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materialPieces = [
            ['name' => 'Schraube M6x30', 'price_in' => 0.15, 'price_out' => 0.25],
            ['name' => 'Dübel 8x60', 'price_in' => 0.08, 'price_out' => 0.15],
            ['name' => 'Schraube M8x40', 'price_in' => 0.22, 'price_out' => 0.35],
            ['name' => 'Winkelverbinder', 'price_in' => 2.50, 'price_out' => 4.00],
            ['name' => 'Metallplatte 50x50mm', 'price_in' => 1.20, 'price_out' => 2.00],
            ['name' => 'Klemmverbinder', 'price_in' => 3.50, 'price_out' => 5.50],
            ['name' => 'Schraube M10x50', 'price_in' => 0.35, 'price_out' => 0.55],
            ['name' => 'Dübel 10x80', 'price_in' => 0.12, 'price_out' => 0.20],
            ['name' => 'Verbindungsplatte', 'price_in' => 4.00, 'price_out' => 6.50],
            ['name' => 'Ankerbolzen M12', 'price_in' => 1.50, 'price_out' => 2.50],
        ];

        DB::table('material_pieces')->insert($materialPieces);
    }
}
