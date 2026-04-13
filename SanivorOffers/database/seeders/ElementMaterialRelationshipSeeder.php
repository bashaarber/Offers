<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Element;
use App\Models\Material;

class ElementMaterialRelationshipSeeder extends Seeder
{
    public function run(): void
    {
        $elements  = Element::all();
        $materials = Material::all();

        $el = $elements->where('name', 'Vorwand Grundrahme')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Vorwand Grundrahme')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Vorwand Grundrahme Teilhoch')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Vorwand Grundrahme Teilhoch')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Vorwand Grundrahme Telihoch und Rahmhoch')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Vorwand Grundrahme Telihoch und Rahmhoch')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Freistehend Grundrahme')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Freistehend Grundrahme')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 8.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Freistehend Grundrahme Hinten leer')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Freistehend Grundrahme Hinten leer')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 8.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Vorwand Grundelement DeBo')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Vorwand Grundelement DeBo')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung DeBo')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Trennwand Grundelement DeBo')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Befestigung DeBo')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            $m = $materials->where('name', 'Trennwand Grundelement DeBo')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Vorwand Aufstock')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Vorwand Aufstock')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Vorwand Aufstock mit Seiten-Abschl.')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Vorwand Aufstock mit Seiten-Abschl.')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Freistehend Austock')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Freistehend Aufstock')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Freistehend Aufstock mit Seiten-Abschl.')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Freistehend Aufstock mit Seiten-Abschl.')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Vorwand Aufstockelement DeBo')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Vorwand Aufstockelement DeBo')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Trennwand Aufstockelement DeBo')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Trennwand Aufstockelement DeBo')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Nische Ca 50cmx30cm (für Dusche/Bad)')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Nische ca. 50cmx30cm (für  Dusche/Bad)')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Nische für Spiegelschrank 80cm x 80cm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Nische für Spiegelschrank 80cm x 80cm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Nische für Spiegelschrank 120cm x 80cm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Nische für Spiegelschrank 120cm x 80cm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Nische für Spiegelschrank 90cm x 60cm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Nische für Spiegelschrank 90cm x 60cm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Wand-WC-Element UP 320, Typ 112')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'UP320, Typ 112')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            $m = $materials->where('name', 'Div. Holz einlagen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 0.5]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Wand-WC-Element UP320, Duofresh, Typ 112')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'UP320, Duofresh, Typ 112')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            $m = $materials->where('name', 'Div. Holz einlagen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 0.5]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Wand-WC-Element UP320, Typ 112, Geruchsabsaugeanschluss')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'UP320, Typ 112, Geruchsabsaugeanschluss')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            $m = $materials->where('name', 'Div. Holz einlagen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 0.5]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Wand-WC-Element UP200, Omega, Typ 85')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'UP200, Omega, Typ 85')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            $m = $materials->where('name', 'Div. Holz einlagen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 0.5]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Wand-WC-Element UP200, Omega, Typ 98')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'UP200, Omega, Typ 98')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            $m = $materials->where('name', 'Div. Holz einlagen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 0.5]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Wand-WC-Element UP200, Omega, Typ 114')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'UP200, Omega, Typ 114')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            $m = $materials->where('name', 'Div. Holz einlagen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 0.5]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Wand-WC für AP-Spühlkasten')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'WC AP 95')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            $m = $materials->where('name', 'Div. Holz einlagen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 0.5]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Waschtisch')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Waschtisch')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Doppel-WT')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Waschtisch')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Behinderten-WT ohne Siphon')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Behinderten-Wt. ohne Siphon')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Behinderten-Wt. mit Siphon')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Behinderten-Wt. mit Siphon')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Waschtisch UP')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Waschtisch UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Waschtisch (ohne Einbaumöbel)')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Waschtisch')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Badewanne AP 153')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Bad AP 153')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Bad AP 153 mit Gleitstange')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Bad AP 153')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Bad AP 153 mit Gleitstange und Glasstrenwand')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Glasstrenwand')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Bad AP 153')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Bad UP')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Bad UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Bad UP mit Gleitstange und Handbrause')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Bad UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Handbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Bad UP mit Gleitstange, Handbrause und Kopfbrause')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Bad UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Kopfbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Handbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Bad UP mit Gleitstange, Handbrause, Kopfbrause und Glasstrenwand')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Bad UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Handbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Kopfbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Glasstrenwande')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Dusche AP 153')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Dusche AP 153')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Dusche AP 153 mit Gleitstange')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Dusche AP 153')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Dusche AP 153 mit Gleitstange und Glasstrenwand')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Dusche AP 153')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Glasstrenwande')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Dusche UP')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Dusche UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Dusche UP mit Gleitstange und Handbrause')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Dusche UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Handbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Dusche UP mit Gleitstange, Handbrause und Kopfbrause')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Dusche UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            $m = $materials->where('name', 'Handbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Kopfbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Dusche UP mit Gleitstange, Handbrause, Kopfbrause und Glasstrenwand')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Dusche UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Glasstrenwande')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Handbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Kopfbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Dusche UP mit Gleitstange, Handbrause und Glasstrenwand')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Handbrause')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Gleitstange')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Dusche UP')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Holz (Sperrholz) 24 mm fur Glasstrenwande')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Bidet')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Bidet')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 4.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Geberit Urinal 137cm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Urinal 137cm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Geberit Urinal 114cm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Urinal 114cm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Geberit Urinal Universal 114cm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Urinal Universal 114cm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Geberit Urinal 123cm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Urinal 123cm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Urinal ohne steurung mit wasseranschl.')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Urinal ohne steurung mit wasseranschl.')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Urinal 0 Liter')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Urinal 0 Liter')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Küche')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Küche')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Holz für Küchenkombination')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Holz fur Küchenkombination')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Waschmaschine onhe UP Siphon')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'WM ohne Siphon')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Waschmaschine mit UP Siphon (Rohbau Set)')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'WM mit Siphon')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Befestigung')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Holz Einlage')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Div. Holz einlagen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Lüftungsgehäuse (Lieferung bauseits)')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Lüftungsgehäuse (Lieferung bauseits)')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Abzweriger 88 1/2° 110/110 mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit PE Langmuffe 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Abzweig 88 1/2° 110/110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Isoliert mit schwer Schallschutzmatte')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Bogenabzweig 88 1/2° 110/110mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit PE Bogenabzweig 88 1/2° 110/110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Langmuffe 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Doppel-Abzweig 180° 110/110mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit PE Langmuffe 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Doppel-Abzweig 180° 110/110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Mehrfachabzweig 3-teilig 110/110mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit PE Mehrfachabzweig 3-teilig 110/110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Langmuffe 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Abzweriger 88 1/2° 125/110mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit PE Abzweig 88 1/2° 125/110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Langmuffe 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Rohrschelle 135 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Doppel-Abzweig 180° 125/110mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit PE Doppel-Abzweig 180° 125/110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Langmuffe 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Rohrschelle 135 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Mehrfachabzweig 3-teilig 135° 125/110mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit PE Mehrfachabzweig 3-teilig 135° 125/110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Langmuffe 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Rohrschelle 135 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Isoliert mit schwer Schallschutzmatte')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Isoliert mit schwer Schallschutzmatte')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Apparate Anschl. in PE-Silent')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Silent Apparate (anschl.)')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Bogenabzweig 88 1/2° 110mm (Silent)')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Silent Bogenabzweig 88 1/2° 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Langmuffe 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Rohrschelle Fixpunkt')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Doppel-Abzweig 180° (L/R)110mm (310.171 / 172) (Silent)')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Silent Doppel-Abzweig 180° (L/R)110 mm (310.171 / 172)')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Langmuffe 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Rohrschelle Fixpunkt')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Doppel-Abzweig 110mm (Silent)')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Silent Doppel-Abzweig 180° 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Langmuffe 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Rohrschelle Fixpunkt')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Abzweig 88 1/2° 135/110mm (Silent)')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Silent Abzweig 88 1/2° 135/110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 135 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Rohrschelle Fixpunkt')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Langmuffe 135 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '56mm Umlüftung PE')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit PE Bogen 45° 56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit PE Reduktion zentrich 110/56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr im Stangen 56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.5]; }
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110/56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '63mm Umlüftung PE')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit PE Bogen 45° 63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit PE Reduktion zentrich 110/63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.5]; }
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110/63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr im Stangen 63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '75mm Umlüftung PE')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.5]; }
            $m = $materials->where('name', 'Geberit PE Bogen 45° 75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit PE Reduktion zentrich 110/75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110/75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr im Stangen 75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '90mm Umlüftung PE')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.5]; }
            $m = $materials->where('name', 'Geberit PE Bogen 45° 90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit PE Reduktion zentrich 110/90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr im Stangen 90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110/90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '110mm Umlüftung PE')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.5]; }
            $m = $materials->where('name', 'Geberit PE Bogen 45° 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            $m = $materials->where('name', 'Geberit PE Rohr im Stangen 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '63mm Umlüftung Silent')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.5]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr im Stangen 63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit Silent Reduktion zentrich 110/63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Bogen 45° 63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110/63 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '75mm Umlüftung Silent')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.5]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr im Stangen 75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit Silent Reduktion zentrich 110/75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Bogen 45° 75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110/75 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '90mm Umlüftung Silent')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.5]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr im Stangen 90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit PE Reduktion zentrich 110/90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Bogen 45° 90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110/90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '110mm Umlüftung Silent')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 5.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            $m = $materials->where('name', 'Geberit Silent Bogen 45° 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', '56 mm Umlüftung Silent')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.5]; }
            $m = $materials->where('name', 'Geberit Silent Bogen 45° 56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit Silent Reduktion zentrich 110/56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr im Stangen 56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 3.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit PE Elektromuffe mit Indikator 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Abzweig 45° 110/56 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Apparate Anschl. in PE')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'PE Apparate (anschl.)')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Steigleitungen CNS')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Steigleitungen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Rehau 16mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Internal Anschlüsse16 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Rehau 20mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Internal Anschlüsse 20 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Sanipex 16mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Internal Anschlüsse16 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Sanipex 20mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Internal Anschlüsse 20 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Nussbaum 16mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Internal Anschlüsse16 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Nussbaum 20mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Internal Anschlüsse 20 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Sanipex 16mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex 16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex  2x16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex 3x16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex 4x16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex 5x16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Sanipex 20mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex 20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex 2x20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex 3x20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex 4x20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Sanipex 5x20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'iFit 16/20mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Externe Anschlüsse iFIT 16/20 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse iFIT 2x16/20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse iFIT 3x16/20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse iFIT 4x16/20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse iFIT 5x16/20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Nussbaum 16mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 2x16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 3x16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 4x16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 5x16mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Nussbaum 20mm')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 2x20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 3x20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 4x20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Externe Anschlüsse Nussbaum 5x20mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'KQ-Compaq small Absperrventile 3/4"  mit 2 Zählergehäuse Koax 2" (ohne Abdeckung - Rosette)')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'KQ-Wasserzählertstrecke')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 2.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Kompakteinheit, Absperrventile 3/4" 2 Zählergehäuse Koax 2"')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Kompakteinheit, Absperrventile 3/4"  2 Zählergehäuse Koax 2"')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Kompakteinheit, Absperrventile 3/4" 1 Zählergehäuse Koax 2"')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Kompakteinheit, Absperrventile 3/4" 1 Zählergehäuse Koax 2"')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Kompakteinheit, Absperrventile 3/4" Ohne Zählergehäuse')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Kompakteinheit, Absperrventile 3/4" Ohne Zählergehäuse')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'GF-JRG UP-Absperr-/Zählereiheit kompakt 3/4\'\' -PN')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'GF-JRG UP-Absperr-/Zählereiheit kompakt 3/4\'\' -PN')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'FS WAR 90 Silent')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Silent Rohr im Stangen 90 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Langmuffe 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Rohrschelle Fixpunkt')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'FS WAR 110 Silent')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Geberit Silent Langmuffe 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Rohrschelle Fixpunkt')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Geberit Silent Rohr 110 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Deckeneinlagen WAS')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Deckeneinlagen WAS')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Inkl. Rohrschelle')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Inkl. Rohrschelle')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Inkl. E-Muffen')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Inkl. E-Muffen')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Inkl. Schalungsschoner')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Inkl. Schalungsschoner')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Inkl. Kanisparblöck')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Inkl. Kanisparblöck')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Deckeneinlagen WAR')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Deckeneinlagen WAR')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Fallstrang Umlenkung Geberit Isol.')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Fallstrang Umlenkung Geberit Isol. (90/ 110)')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

        $el = $elements->where('name', 'Rohrschelle')->first();
        if ($el) {
            $attach = [];
            $m = $materials->where('name', 'Rohrschelle 125 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Rohrschelle 135 mm')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            $m = $materials->where('name', 'Inkl. Rohrschelle')->first();
            if ($m) { $attach[$m->id] = ['quantity' => 1.0]; }
            if ($attach) { $el->materials()->syncWithoutDetaching($attach); }
        }

    }
}
