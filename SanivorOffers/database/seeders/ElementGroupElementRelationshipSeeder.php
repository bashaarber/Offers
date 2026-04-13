<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Element;
use App\Models\GroupElement;

class ElementGroupElementRelationshipSeeder extends Seeder
{
    public function run(): void
    {
        $elements = Element::all();
        $groupElements = GroupElement::all();

        $ge_ge = $groupElements->where('name', 'Grundrahme')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Vorwand Grundrahme')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Vorwand Grundrahme Teilhoch')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Vorwand Grundrahme Telihoch und Rahmhoch')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Freistehend Grundrahme')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Freistehend Grundrahme Hinten leer')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Vorwand Grundelement DeBo')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Trennwand Grundelement DeBo')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Aufstock')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Vorwand Aufstock')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Vorwand Aufstock mit Seiten-Abschl.')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Freistehend Austock')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Freistehend Aufstock mit Seiten-Abschl.')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Vorwand Aufstockelement DeBo')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Trennwand Aufstockelement DeBo')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Nische')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Nische Ca 50cmx30cm (für Dusche/Bad)')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Nische für Spiegelschrank 80cm x 80cm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Nische für Spiegelschrank 120cm x 80cm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Nische für Spiegelschrank 90cm x 60cm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Wand-WC')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Wand-WC-Element UP 320, Typ 112')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Wand-WC-Element UP320, Duofresh, Typ 112')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Wand-WC-Element UP320, Typ 112, Geruchsabsaugeanschluss')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Wand-WC-Element UP200, Omega, Typ 85')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Wand-WC-Element UP200, Omega, Typ 98')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Wand-WC-Element UP200, Omega, Typ 114')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Wand-WC für AP-Spühlkasten')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Waschtisch')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Waschtisch')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Doppel-WT')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Behinderten-WT ohne Siphon')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Behinderten-Wt. mit Siphon')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Waschtisch UP')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Waschtisch (ohne Einbaumöbel)')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Badewanne')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Badewanne AP 153')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Bad AP 153 mit Gleitstange')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Bad AP 153 mit Gleitstange und Glasstrenwand')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Bad UP')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Bad UP mit Gleitstange und Handbrause')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Bad UP mit Gleitstange, Handbrause und Kopfbrause')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Bad UP mit Gleitstange, Handbrause, Kopfbrause und Glasstrenwand')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Dusche')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Dusche AP 153')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Dusche AP 153 mit Gleitstange')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Dusche AP 153 mit Gleitstange und Glasstrenwand')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Dusche UP')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Dusche UP mit Gleitstange und Handbrause')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Dusche UP mit Gleitstange, Handbrause und Kopfbrause')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Dusche UP mit Gleitstange, Handbrause, Kopfbrause und Glasstrenwand')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Dusche UP mit Gleitstange, Handbrause und Glasstrenwand')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Didet')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Bidet')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Urinal')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Geberit Urinal 137cm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Geberit Urinal 114cm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Geberit Urinal Universal 114cm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Geberit Urinal 123cm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Urinal ohne steurung mit wasseranschl.')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Urinal 0 Liter')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Küche')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Küche')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Holz für Küchenkombination')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Waschmachine')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Waschmaschine onhe UP Siphon')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Waschmaschine mit UP Siphon (Rohbau Set)')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Holz')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Holz Einlage')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Montage Lüftungsgehäuse')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Lüftungsgehäuse (Lieferung bauseits)')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Fallstrang PE mit')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Abzweriger 88 1/2° 110/110 mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Bogenabzweig 88 1/2° 110/110mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Doppel-Abzweig 180° 110/110mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Mehrfachabzweig 3-teilig 110/110mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Abzweriger 88 1/2° 125/110mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Doppel-Abzweig 180° 125/110mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Mehrfachabzweig 3-teilig 135° 125/110mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Isoliert mit schwer Schallschutzmatte')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Apparate Anschl. in PE-Silent')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'FS WAS Silent mit')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Bogenabzweig 88 1/2° 110mm (Silent)')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Doppel-Abzweig 180° (L/R)110mm (310.171 / 172) (Silent)')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Doppel-Abzweig 110mm (Silent)')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Abzweig 88 1/2° 135/110mm (Silent)')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Isoliert mit schwer Schallschutzmatte')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Umlüftung PE mit')->first();
        if ($ge_ge) {
            $el = $elements->where('name', '56mm Umlüftung PE')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', '63mm Umlüftung PE')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', '75mm Umlüftung PE')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', '90mm Umlüftung PE')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', '110mm Umlüftung PE')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Umlüftung Silent mit')->first();
        if ($ge_ge) {
            $el = $elements->where('name', '63mm Umlüftung Silent')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', '75mm Umlüftung Silent')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', '90mm Umlüftung Silent')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', '110mm Umlüftung Silent')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', '56 mm Umlüftung Silent')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Apparate Anschl. WAS.')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Apparate Anschl. in PE')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Apparate Anschl. in PE-Silent')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Steigleitungen')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Steigleitungen CNS')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Interne Wasser Anschl.')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Rehau 16mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Rehau 20mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Sanipex 16mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Sanipex 20mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Nussbaum 16mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Nussbaum 20mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Externe Wasser Anschl.')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Sanipex 16mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Sanipex 20mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'iFit 16/20mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Nussbaum 16mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Nussbaum 20mm')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Wasserzählertstrecke')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'KQ-Compaq small Absperrventile 3/4"  mit 2 Zählergehäuse Koax 2" (ohne Abdeckung - Rosette)')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Kompakteinheit, Absperrventile 3/4" 2 Zählergehäuse Koax 2"')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Kompakteinheit, Absperrventile 3/4" 1 Zählergehäuse Koax 2"')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Kompakteinheit, Absperrventile 3/4" Ohne Zählergehäuse')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'GF-JRG UP-Absperr-/Zählereiheit kompakt 3/4\'\' -PN')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'FS WAR Silent mit')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'FS WAR 90 Silent')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'FS WAR 110 Silent')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Deckeneinlagen WAS')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Deckeneinlagen WAS')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Inkl. Rohrschelle')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Inkl. E-Muffen')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Inkl. Schalungsschoner')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Inkl. Kanisparblöck')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Deckeneinlagen WAR')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Deckeneinlagen WAR')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Inkl. Rohrschelle')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Inkl. E-Muffen')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Inkl. Schalungsschoner')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
            $el = $elements->where('name', 'Inkl. Kanisparblöck')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'FS Umlenkung Geberit Isol.')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Fallstrang Umlenkung Geberit Isol.')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

        $ge_ge = $groupElements->where('name', 'Kanalistation')->first();
        if ($ge_ge) {
            $el = $elements->where('name', 'Rohrschelle')->first();
            if ($el && !$ge_ge->elements()->where('element_id', $el->id)->exists()) { $ge_ge->elements()->attach($el->id); }
        }

    }
}
