<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Element;
use App\Models\Material;

class ElementMaterialRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elements = Element::all();
        $materials = Material::all();

        // Standard wall construction - uses steel profile, drywall, metal studs, insulation
        $standardWall = $elements->where('name', 'Wandkonstruktion Standard')->first();
        if ($standardWall) {
            $standardWall->materials()->attach([
                $materials->where('name', 'Stahlprofil 100x50mm')->first()->id => ['quantity' => 2.5],
                $materials->where('name', 'Gipskartonplatte 12.5mm')->first()->id => ['quantity' => 2.0],
                $materials->where('name', 'Metallständer 75mm')->first()->id => ['quantity' => 3.0],
                $materials->where('name', 'Dämmstoff 60mm')->first()->id => ['quantity' => 1.0],
            ]);
        }

        // High partition wall - uses larger steel profile, Fermacell, insulation
        $highPartition = $elements->where('name', 'Trennwand Raumhoch')->first();
        if ($highPartition) {
            $highPartition->materials()->attach([
                $materials->where('name', 'Stahlprofil 150x75mm')->first()->id => ['quantity' => 3.0],
                $materials->where('name', 'Fermacellplatte 12.5mm')->first()->id => ['quantity' => 2.0],
                $materials->where('name', 'Dämmstoff 60mm')->first()->id => ['quantity' => 1.0],
            ]);
        }

        // DeBO System wall - uses steel profile, Fermacell
        $deboWall = $elements->where('name', 'Vorwand DeBO-System')->first();
        if ($deboWall) {
            $deboWall->materials()->attach([
                $materials->where('name', 'Stahlprofil 150x75mm')->first()->id => ['quantity' => 3.5],
                $materials->where('name', 'Fermacellplatte 12.5mm')->first()->id => ['quantity' => 2.0],
            ]);
        }

        // Freestanding wall - uses steel profile, drywall
        $freestanding = $elements->where('name', 'Freistehende Wand')->first();
        if ($freestanding) {
            $freestanding->materials()->attach([
                $materials->where('name', 'Stahlprofil 100x50mm')->first()->id => ['quantity' => 2.0],
                $materials->where('name', 'Gipskartonplatte 15mm')->first()->id => ['quantity' => 2.0],
            ]);
        }

        // Wall with door opening - uses steel profile, drywall, door frame
        $wallWithDoor = $elements->where('name', 'Wand mit Türöffnung')->first();
        if ($wallWithDoor) {
            $wallWithDoor->materials()->attach([
                $materials->where('name', 'Stahlprofil 100x50mm')->first()->id => ['quantity' => 2.5],
                $materials->where('name', 'Gipskartonplatte 12.5mm')->first()->id => ['quantity' => 2.0],
                $materials->where('name', 'Türzarge Metall')->first()->id => ['quantity' => 1.0],
            ]);
        }

        // Fire protection wall - uses steel profile, Fermacell
        $fireWall = $elements->where('name', 'Brandschutzwand')->first();
        if ($fireWall) {
            $fireWall->materials()->attach([
                $materials->where('name', 'Stahlprofil 150x75mm')->first()->id => ['quantity' => 3.0],
                $materials->where('name', 'Fermacellplatte 12.5mm')->first()->id => ['quantity' => 2.0],
            ]);
        }

        // Soundproofing wall - uses steel profile, drywall, insulation
        $soundWall = $elements->where('name', 'Schallschutzwand')->first();
        if ($soundWall) {
            $soundWall->materials()->attach([
                $materials->where('name', 'Stahlprofil 100x50mm')->first()->id => ['quantity' => 2.5],
                $materials->where('name', 'Gipskartonplatte 15mm')->first()->id => ['quantity' => 2.0],
                $materials->where('name', 'Dämmstoff 60mm')->first()->id => ['quantity' => 1.5],
            ]);
        }

        // Wall with window opening - uses steel profile, drywall
        $wallWithWindow = $elements->where('name', 'Wand mit Fensteröffnung')->first();
        if ($wallWithWindow) {
            $wallWithWindow->materials()->attach([
                $materials->where('name', 'Stahlprofil 100x50mm')->first()->id => ['quantity' => 2.5],
                $materials->where('name', 'Gipskartonplatte 12.5mm')->first()->id => ['quantity' => 2.0],
            ]);
        }

        // Partial height partition - uses smaller steel profile, drywall
        $partialWall = $elements->where('name', 'Trennwand Teilhoch')->first();
        if ($partialWall) {
            $partialWall->materials()->attach([
                $materials->where('name', 'Stahlprofil 100x50mm')->first()->id => ['quantity' => 1.5],
                $materials->where('name', 'Gipskartonplatte 12.5mm')->first()->id => ['quantity' => 1.5],
            ]);
        }

        // High front wall - uses steel profile, drywall
        $highFrontWall = $elements->where('name', 'Vorwand Raumhoch')->first();
        if ($highFrontWall) {
            $highFrontWall->materials()->attach([
                $materials->where('name', 'Stahlprofil 100x50mm')->first()->id => ['quantity' => 2.5],
                $materials->where('name', 'Gipskartonplatte 12.5mm')->first()->id => ['quantity' => 2.0],
                $materials->where('name', 'Metallständer 75mm')->first()->id => ['quantity' => 3.0],
            ]);
        }
    }
}
