<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CoefficientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coefficient = DB::table('coefficients')->first();
        
        if (! $coefficient) {
            $row = [
                'validity' => '90 Tage',
                'labor_cost' => 50,
                'labor_price' => 87.5,
                'service' => '2 bis 4 Wochen nach GZA',
                'material' => 1.3,
                'difficulty' => 0.7,
                'payment_conditions' => '30 Tage Netto',
            ];
            if (Schema::hasColumn('coefficients', 'default_rabatt')) {
                $row['default_rabatt'] = 20;
            }
            if (Schema::hasColumn('coefficients', 'default_signature')) {
                $row['default_signature'] = 'Arber Basha';
            }
            DB::table('coefficients')->insert($row);
        }
    }
}
