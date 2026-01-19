<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GroupElement;
use App\Models\Position;

class GroupElementPositionRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groupElements = GroupElement::all();
        $positions = Position::all();

        // Position 1: Vorwand Raumhoch - belongs to Standard Wände
        $position1 = $positions->where('position_number', 1)->first();
        if ($position1) {
            $position1->group_elements()->attach([
                $groupElements->where('name', 'Standard Wände')->first()->id,
            ]);
        }

        // Position 2: Trennwand DeBO-System - belongs to DeBO System and Fire Protection
        $position2 = $positions->where('position_number', 2)->first();
        if ($position2) {
            $position2->group_elements()->attach([
                $groupElements->where('name', 'DeBO System Wände')->first()->id,
                $groupElements->where('name', 'Brandschutz Wände')->first()->id,
            ]);
        }

        // Position 3: Freistehende Wand - belongs to Freestanding and Soundproofing
        $position3 = $positions->where('position_number', 3)->first();
        if ($position3) {
            $position3->group_elements()->attach([
                $groupElements->where('name', 'Freistehende Konstruktionen')->first()->id,
                $groupElements->where('name', 'Schallschutz Wände')->first()->id,
            ]);
        }

        // Position 4: Vorwand Teilhoch - belongs to Standard Wände
        $position4 = $positions->where('position_number', 4)->first();
        if ($position4) {
            $position4->group_elements()->attach([
                $groupElements->where('name', 'Standard Wände')->first()->id,
            ]);
        }

        // Position 5: Trennwand Raumhoch und Teilhoch - belongs to Standard Wände
        $position5 = $positions->where('position_number', 5)->first();
        if ($position5) {
            $position5->group_elements()->attach([
                $groupElements->where('name', 'Standard Wände')->first()->id,
            ]);
        }
    }
}
