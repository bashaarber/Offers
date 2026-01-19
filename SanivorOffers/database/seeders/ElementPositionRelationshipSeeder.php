<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Element;
use App\Models\Position;

class ElementPositionRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elements = Element::all();
        $positions = Position::all();

        // Position 1: Vorwand Raumhoch - uses standard wall construction
        $position1 = $positions->where('position_number', 1)->first();
        if ($position1) {
            $position1->elements()->attach([
                $elements->where('name', 'Wandkonstruktion Standard')->first()->id => ['quantity' => 1],
                $elements->where('name', 'Vorwand Raumhoch')->first()->id => ['quantity' => 1],
            ]);
        }

        // Position 2: Trennwand DeBO-System - uses DeBO system wall
        $position2 = $positions->where('position_number', 2)->first();
        if ($position2) {
            $position2->elements()->attach([
                $elements->where('name', 'Vorwand DeBO-System')->first()->id => ['quantity' => 1],
                $elements->where('name', 'Brandschutzwand')->first()->id => ['quantity' => 1],
            ]);
        }

        // Position 3: Freistehende Wand Raumhoch - uses freestanding wall and soundproofing
        $position3 = $positions->where('position_number', 3)->first();
        if ($position3) {
            $position3->elements()->attach([
                $elements->where('name', 'Freistehende Wand')->first()->id => ['quantity' => 1],
                $elements->where('name', 'Schallschutzwand')->first()->id => ['quantity' => 1],
            ]);
        }

        // Position 4: Vorwand Teilhoch - uses partial height partition
        $position4 = $positions->where('position_number', 4)->first();
        if ($position4) {
            $position4->elements()->attach([
                $elements->where('name', 'Trennwand Teilhoch')->first()->id => ['quantity' => 1],
            ]);
        }

        // Position 5: Trennwand Raumhoch und Teilhoch - uses high partition wall
        $position5 = $positions->where('position_number', 5)->first();
        if ($position5) {
            $position5->elements()->attach([
                $elements->where('name', 'Trennwand Raumhoch')->first()->id => ['quantity' => 1],
                $elements->where('name', 'Trennwand Teilhoch')->first()->id => ['quantity' => 1],
            ]);
        }
    }
}
