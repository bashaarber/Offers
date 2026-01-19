<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\MaterialPiece;

class MaterialMaterialPieceRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get materials and material pieces
        $materials = Material::all();
        $materialPieces = MaterialPiece::all();

        // Connect steel profiles with screws, anchors, and connectors
        $steelProfiles = $materials->whereIn('name', ['Stahlprofil 100x50mm', 'Stahlprofil 150x75mm']);
        foreach ($steelProfiles as $material) {
            $material->material_pieces()->attach([
                $materialPieces->where('name', 'Schraube M6x30')->first()->id,
                $materialPieces->where('name', 'Schraube M8x40')->first()->id,
                $materialPieces->where('name', 'Schraube M10x50')->first()->id,
                $materialPieces->where('name', 'Dübel 8x60')->first()->id,
                $materialPieces->where('name', 'Dübel 10x80')->first()->id,
                $materialPieces->where('name', 'Winkelverbinder')->first()->id,
            ]);
        }

        // Connect metal studs with screws and anchors
        $metalStuds = $materials->where('name', 'Metallständer 75mm');
        foreach ($metalStuds as $material) {
            $material->material_pieces()->attach([
                $materialPieces->where('name', 'Schraube M6x30')->first()->id,
                $materialPieces->where('name', 'Dübel 8x60')->first()->id,
                $materialPieces->where('name', 'Klemmverbinder')->first()->id,
            ]);
        }

        // Connect door frame with screws, anchors, and plates
        $doorFrames = $materials->where('name', 'Türzarge Metall');
        foreach ($doorFrames as $material) {
            $material->material_pieces()->attach([
                $materialPieces->where('name', 'Schraube M8x40')->first()->id,
                $materialPieces->where('name', 'Schraube M10x50')->first()->id,
                $materialPieces->where('name', 'Dübel 10x80')->first()->id,
                $materialPieces->where('name', 'Metallplatte 50x50mm')->first()->id,
                $materialPieces->where('name', 'Ankerbolzen M12')->first()->id,
            ]);
        }

        // Connect drywall boards with screws
        $drywallBoards = $materials->whereIn('name', ['Gipskartonplatte 12.5mm', 'Gipskartonplatte 15mm']);
        foreach ($drywallBoards as $material) {
            $material->material_pieces()->attach([
                $materialPieces->where('name', 'Schraube M6x30')->first()->id,
            ]);
        }

        // Connect Fermacell boards with screws
        $fermacellBoards = $materials->where('name', 'Fermacellplatte 12.5mm');
        foreach ($fermacellBoards as $material) {
            $material->material_pieces()->attach([
                $materialPieces->where('name', 'Schraube M8x40')->first()->id,
                $materialPieces->where('name', 'Verbindungsplatte')->first()->id,
            ]);
        }
    }
}
