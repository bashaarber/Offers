<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Element;
use App\Models\GroupElement;

class ElementGroupElementRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elements = Element::all();
        $groupElements = GroupElement::all();

        // Standard Walls group
        $standardWallsGroup = $groupElements->where('name', 'Standard Wände')->first();
        if ($standardWallsGroup) {
            $standardWallsGroup->elements()->attach([
                $elements->where('name', 'Wandkonstruktion Standard')->first()->id,
                $elements->where('name', 'Vorwand Raumhoch')->first()->id,
                $elements->where('name', 'Trennwand Teilhoch')->first()->id,
            ]);
        }

        // Fire Protection Walls group
        $fireWallsGroup = $groupElements->where('name', 'Brandschutz Wände')->first();
        if ($fireWallsGroup) {
            $fireWallsGroup->elements()->attach([
                $elements->where('name', 'Brandschutzwand')->first()->id,
                $elements->where('name', 'Vorwand DeBO-System')->first()->id,
            ]);
        }

        // Soundproofing Walls group
        $soundWallsGroup = $groupElements->where('name', 'Schallschutz Wände')->first();
        if ($soundWallsGroup) {
            $soundWallsGroup->elements()->attach([
                $elements->where('name', 'Schallschutzwand')->first()->id,
                $elements->where('name', 'Trennwand Raumhoch')->first()->id,
            ]);
        }

        // DeBO System Walls group
        $deboWallsGroup = $groupElements->where('name', 'DeBO System Wände')->first();
        if ($deboWallsGroup) {
            $deboWallsGroup->elements()->attach([
                $elements->where('name', 'Vorwand DeBO-System')->first()->id,
            ]);
        }

        // Freestanding Constructions group
        $freestandingGroup = $groupElements->where('name', 'Freistehende Konstruktionen')->first();
        if ($freestandingGroup) {
            $freestandingGroup->elements()->attach([
                $elements->where('name', 'Freistehende Wand')->first()->id,
            ]);
        }

        // Walls with Openings group
        $openingsGroup = $groupElements->where('name', 'Wände mit Öffnungen')->first();
        if ($openingsGroup) {
            $openingsGroup->elements()->attach([
                $elements->where('name', 'Wand mit Türöffnung')->first()->id,
                $elements->where('name', 'Wand mit Fensteröffnung')->first()->id,
            ]);
        }
    }
}
