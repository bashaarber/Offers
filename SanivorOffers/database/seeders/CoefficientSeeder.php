<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoefficientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coefficient = DB::table('coefficients')->first();
        
        if (!$coefficient) {
            DB::table('coefficients')->insert([
                'validity' => '90 Tage',
                'labor_cost' => 50,
                'labor_price' => 87.5,
                'service' => '2 bis 4 Wochen nach GZA',
                'material' => 1.3,
                'difficulty' => 0.7,
                'payment_conditions' => '30 Tage Netto',
            ]);
        }
    }
}
