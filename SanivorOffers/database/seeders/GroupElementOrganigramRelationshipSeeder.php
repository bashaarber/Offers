<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GroupElement;
use App\Models\Organigram;

class GroupElementOrganigramRelationshipSeeder extends Seeder
{
    public function run(): void
    {
        $groupElements = GroupElement::all();
        $organigrams = Organigram::all();

        $org_Rahme = $organigrams->where('name', 'Rahme')->first();
        if ($org_Rahme) {
            $ge = $groupElements->where('name', 'Grundrahme')->first();
            if ($ge) { $org_Rahme->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Aufstock')->first();
            if ($ge) { $org_Rahme->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Nische')->first();
            if ($ge) { $org_Rahme->group_elements()->attach($ge->id); }
        }

        $org_Installationsmodule = $organigrams->where('name', 'Installationsmodule')->first();
        if ($org_Installationsmodule) {
            $ge = $groupElements->where('name', 'Wand-WC')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Waschtisch')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Badewanne')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Dusche')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Didet')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Urinal')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Küche')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Waschmachine')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Holz')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Montage Lüftungsgehäuse')->first();
            if ($ge) { $org_Installationsmodule->group_elements()->attach($ge->id); }
        }

        $org_Verrohrung = $organigrams->where('name', 'Verrohrung')->first();
        if ($org_Verrohrung) {
            $ge = $groupElements->where('name', 'Fallstrang PE mit')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'FS WAS Silent mit')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Umlüftung PE mit')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Umlüftung Silent mit')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Apparate Anschl. WAS.')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Steigleitungen')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Interne Wasser Anschl.')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Externe Wasser Anschl.')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Wasserzählertstrecke')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'FS WAR Silent mit')->first();
            if ($ge) { $org_Verrohrung->group_elements()->attach($ge->id); }
        }

        $org_PEVorfabrikation = $organigrams->where('name', 'PE-Vorfabrikation')->first();
        if ($org_PEVorfabrikation) {
            $ge = $groupElements->where('name', 'Deckeneinlagen WAS')->first();
            if ($ge) { $org_PEVorfabrikation->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'Deckeneinlagen WAR')->first();
            if ($ge) { $org_PEVorfabrikation->group_elements()->attach($ge->id); }
            $ge = $groupElements->where('name', 'FS Umlenkung Geberit Isol.')->first();
            if ($ge) { $org_PEVorfabrikation->group_elements()->attach($ge->id); }
        }

        $org_Kanslaisation = $organigrams->where('name', 'Kanslaisation')->first();
        if ($org_Kanslaisation) {
            $ge = $groupElements->where('name', 'Kanalistation')->first();
            if ($ge) { $org_Kanslaisation->group_elements()->attach($ge->id); }
        }

    }
}
