<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'ABC Construction GmbH',
                'email' => 'contact@abcconstruction.de',
                'number' => '+49 30 12345678',
                'address' => 'Musterstraße 123, 10115 Berlin',
            ],
            [
                'name' => 'XYZ Bauunternehmen',
                'email' => 'info@xyzbau.de',
                'number' => '+49 40 98765432',
                'address' => 'Hauptstraße 45, 20095 Hamburg',
            ],
            [
                'name' => 'Modern Building Solutions',
                'email' => 'hello@modernbuilding.de',
                'number' => '+49 89 55512345',
                'address' => 'Bauernweg 78, 80331 München',
            ],
            [
                'name' => 'Premium Renovations',
                'email' => 'info@premiumrenovations.de',
                'number' => '+49 221 44456789',
                'address' => 'Kölner Straße 12, 50667 Köln',
            ],
            [
                'name' => 'Elite Construction Group',
                'email' => 'contact@eliteconstruction.de',
                'number' => '+49 711 33398765',
                'address' => 'Königstraße 56, 70173 Stuttgart',
            ],
        ];

        DB::table('clients')->insert($clients);
    }
}
