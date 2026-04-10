<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materials = [
            [
                'name' => 'Stahlprofil 100x50mm',
                'unit' => 'm',
                'price_in' => 25.50,
                'price_out' => 42.00,
                'z_schlosserei' => 15.00,
                'z_pe' => '2.5',
                'z_montage' => '3.0',
                'z_fermacell' => '1.5',
                'z_total' => 22.00,
                'zeit_cost' => 18.50,
                'total' => 64.50,
            ],
            [
                'name' => 'Gipskartonplatte 12.5mm',
                'unit' => 'm²',
                'price_in' => 8.50,
                'price_out' => 14.00,
                'z_schlosserei' => 0.00,
                'z_pe' => '1.0',
                'z_montage' => '2.5',
                'z_fermacell' => '0.0',
                'z_total' => 3.50,
                'zeit_cost' => 12.00,
                'total' => 17.50,
            ],
            [
                'name' => 'Fermacellplatte 12.5mm',
                'unit' => 'm²',
                'price_in' => 12.00,
                'price_out' => 20.00,
                'z_schlosserei' => 0.00,
                'z_pe' => '1.2',
                'z_montage' => '2.8',
                'z_fermacell' => '1.0',
                'z_total' => 5.00,
                'zeit_cost' => 15.00,
                'total' => 25.00,
            ],
            [
                'name' => 'Metallständer 75mm',
                'unit' => 'm',
                'price_in' => 3.20,
                'price_out' => 5.50,
                'z_schlosserei' => 8.00,
                'z_pe' => '1.5',
                'z_montage' => '2.0',
                'z_fermacell' => '0.5',
                'z_total' => 4.00,
                'zeit_cost' => 10.00,
                'total' => 9.50,
            ],
            [
                'name' => 'Dämmstoff 60mm',
                'unit' => 'm²',
                'price_in' => 6.50,
                'price_out' => 11.00,
                'z_schlosserei' => 0.00,
                'z_pe' => '0.8',
                'z_montage' => '1.5',
                'z_fermacell' => '0.0',
                'z_total' => 2.30,
                'zeit_cost' => 8.00,
                'total' => 13.30,
            ],
            [
                'name' => 'Stahlprofil 150x75mm',
                'unit' => 'm',
                'price_in' => 38.00,
                'price_out' => 62.00,
                'z_schlosserei' => 20.00,
                'z_pe' => '3.0',
                'z_montage' => '3.5',
                'z_fermacell' => '2.0',
                'z_total' => 28.50,
                'zeit_cost' => 25.00,
                'total' => 90.50,
            ],
            [
                'name' => 'Gipskartonplatte 15mm',
                'unit' => 'm²',
                'price_in' => 10.50,
                'price_out' => 17.50,
                'z_schlosserei' => 0.00,
                'z_pe' => '1.2',
                'z_montage' => '2.8',
                'z_fermacell' => '0.0',
                'z_total' => 4.00,
                'zeit_cost' => 14.00,
                'total' => 21.50,
            ],
            [
                'name' => 'Türzarge Metall',
                'unit' => 'Stk',
                'price_in' => 85.00,
                'price_out' => 140.00,
                'z_schlosserei' => 45.00,
                'z_pe' => '4.0',
                'z_montage' => '5.0',
                'z_fermacell' => '1.0',
                'z_total' => 54.00,
                'zeit_cost' => 60.00,
                'total' => 199.00,
            ],
        ];

        foreach ($materials as &$m) {
            unset($m['z_fermacell']);
            $oldZ = (float) $m['z_total'];
            $newZ = (float) $m['z_schlosserei'] + (float) $m['z_pe'] + (float) $m['z_montage'];
            $oldZeit = (float) $m['zeit_cost'];
            $m['z_total'] = $newZ;
            $m['zeit_cost'] = $oldZ > 0 ? round($oldZeit * ($newZ / $oldZ), 2) : 0;
            $m['total'] = round((float) $m['total'] - $oldZeit + $m['zeit_cost'], 2);
            if (Schema::hasColumn('materials', 'total_arbeit')) {
                $m['total_arbeit'] = $m['zeit_cost'];
            }
        }
        unset($m);

        DB::table('materials')->insert($materials);
    }
}
