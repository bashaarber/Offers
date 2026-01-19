<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GroupElement;
use App\Models\Organigram;

class GroupElementOrganigramRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupElements = GroupElement::all();
        $organigrams = Organigram::all();

        // Office Buildings - uses standard walls, walls with openings, soundproofing
        $officeBuildings = $organigrams->where('name', 'Bürogebäude')->first();
        if ($officeBuildings) {
            $officeBuildings->group_elements()->attach([
                $groupElements->where('name', 'Standard Wände')->first()->id,
                $groupElements->where('name', 'Wände mit Öffnungen')->first()->id,
                $groupElements->where('name', 'Schallschutz Wände')->first()->id,
            ]);
        }

        // Residential Buildings - uses standard walls, freestanding, walls with openings
        $residentialBuildings = $organigrams->where('name', 'Wohngebäude')->first();
        if ($residentialBuildings) {
            $residentialBuildings->group_elements()->attach([
                $groupElements->where('name', 'Standard Wände')->first()->id,
                $groupElements->where('name', 'Freistehende Konstruktionen')->first()->id,
                $groupElements->where('name', 'Wände mit Öffnungen')->first()->id,
            ]);
        }

        // Industrial Buildings - uses DeBO system, fire protection walls
        $industrialBuildings = $organigrams->where('name', 'Industriegebäude')->first();
        if ($industrialBuildings) {
            $industrialBuildings->group_elements()->attach([
                $groupElements->where('name', 'DeBO System Wände')->first()->id,
                $groupElements->where('name', 'Brandschutz Wände')->first()->id,
            ]);
        }

        // Commercial Buildings - uses standard walls, walls with openings, soundproofing
        $commercialBuildings = $organigrams->where('name', 'Gewerbegebäude')->first();
        if ($commercialBuildings) {
            $commercialBuildings->group_elements()->attach([
                $groupElements->where('name', 'Standard Wände')->first()->id,
                $groupElements->where('name', 'Wände mit Öffnungen')->first()->id,
                $groupElements->where('name', 'Schallschutz Wände')->first()->id,
            ]);
        }

        // Renovation - uses freestanding constructions, standard walls
        $renovation = $organigrams->where('name', 'Sanierung')->first();
        if ($renovation) {
            $renovation->group_elements()->attach([
                $groupElements->where('name', 'Freistehende Konstruktionen')->first()->id,
                $groupElements->where('name', 'Standard Wände')->first()->id,
            ]);
        }
    }
}
