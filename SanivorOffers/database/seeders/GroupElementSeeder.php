<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupElementSeeder extends Seeder
{
    public function run(): void
    {
        $groupElements = [
            ['name' => 'Grundrahme'],
            ['name' => 'Aufstock'],
            ['name' => 'Nische'],
            ['name' => 'Wand-WC'],
            ['name' => 'Waschtisch'],
            ['name' => 'Badewanne'],
            ['name' => 'Dusche'],
            ['name' => 'Didet'],
            ['name' => 'Urinal'],
            ['name' => 'Küche'],
            ['name' => 'Waschmachine'],
            ['name' => 'Holz'],
            ['name' => 'Montage Lüftungsgehäuse'],
            ['name' => 'Fallstrang PE mit'],
            ['name' => 'FS WAS Silent mit'],
            ['name' => 'Umlüftung PE mit'],
            ['name' => 'Umlüftung Silent mit'],
            ['name' => 'Apparate Anschl. WAS.'],
            ['name' => 'Steigleitungen'],
            ['name' => 'Interne Wasser Anschl.'],
            ['name' => 'Externe Wasser Anschl.'],
            ['name' => 'Wasserzählertstrecke'],
            ['name' => 'FS WAR Silent mit'],
            ['name' => 'Deckeneinlagen WAS'],
            ['name' => 'Deckeneinlagen WAR'],
            ['name' => 'FS Umlenkung Geberit Isol.'],
            ['name' => 'Kanalistation'],
        ];

        DB::table('group_elements')->insert($groupElements);
    }
}
