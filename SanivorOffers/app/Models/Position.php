<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Schema;

class Position extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'description2',
        'blocktype',
        'b',
        'h',
        't',
        'price_brutto',
        'price_discount',
        'discount',
        'quantity',
        'material_brutto',
        'zeit_brutto',
        'material_costo',
        'material_profit',
        'ziet_costo',
        'ziet_profit',
        'costo_total',
        'profit_total',
        'position_number',
        'is_optional'
    ];
   
    public function offerts():BelongsToMany
    {
        return $this->belongsToMany(Offert::class)->withTimestamps();
    }

    public function organigrams():BelongsToMany
    {
        return $this->belongsToMany(Organigram::class)->withTimestamps();
    }

    public function group_elements():BelongsToMany
    {
        return $this->belongsToMany(GroupElement::class)->withTimestamps();
    }
    
    public function elements():BelongsToMany
    {
        $relation = $this->belongsToMany(Element::class)->withPivot('quantity');
        if (Schema::hasColumn('element_position', 'is_optional')) {
            $relation->withPivot('is_optional');
        }

        return $relation->withTimestamps();
    }

    public function elementsForPdf(): BelongsToMany
    {
        return $this->belongsToMany(Element::class)->withPivot('quantity')->withTimestamps();
    }
}
