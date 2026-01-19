<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organigram;
use App\Models\Position;

class OrganigramPositionRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organigrams = Organigram::all();
        $positions = Position::all();

        // Office Buildings - uses positions 1, 3, 4
        $officeBuildings = $organigrams->where('name', 'Bürogebäude')->first();
        if ($officeBuildings) {
            $officeBuildings->positions()->attach([
                $positions->where('position_number', 1)->first()->id,
                $positions->where('position_number', 3)->first()->id,
                $positions->where('position_number', 4)->first()->id,
            ]);
        }

        // Residential Buildings - uses positions 1, 4, 5
        $residentialBuildings = $organigrams->where('name', 'Wohngebäude')->first();
        if ($residentialBuildings) {
            $residentialBuildings->positions()->attach([
                $positions->where('position_number', 1)->first()->id,
                $positions->where('position_number', 4)->first()->id,
                $positions->where('position_number', 5)->first()->id,
            ]);
        }

        // Industrial Buildings - uses positions 2
        $industrialBuildings = $organigrams->where('name', 'Industriegebäude')->first();
        if ($industrialBuildings) {
            $industrialBuildings->positions()->attach([
                $positions->where('position_number', 2)->first()->id,
            ]);
        }

        // Commercial Buildings - uses positions 1, 3, 5
        $commercialBuildings = $organigrams->where('name', 'Gewerbegebäude')->first();
        if ($commercialBuildings) {
            $commercialBuildings->positions()->attach([
                $positions->where('position_number', 1)->first()->id,
                $positions->where('position_number', 3)->first()->id,
                $positions->where('position_number', 5)->first()->id,
            ]);
        }

        // Renovation - uses positions 3, 4
        $renovation = $organigrams->where('name', 'Sanierung')->first();
        if ($renovation) {
            $renovation->positions()->attach([
                $positions->where('position_number', 3)->first()->id,
                $positions->where('position_number', 4)->first()->id,
            ]);
        }
    }
}
