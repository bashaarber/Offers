<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Schema;

class Element extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

   public function materials():BelongsToMany
   {
       $relation = $this->belongsToMany(Material::class)->withPivot('quantity');
       if (Schema::hasColumn('element_material', 'sort_order')) {
           $relation->withPivot('sort_order')->orderByPivot('sort_order');
       }

       return $relation->withTimestamps();
   }

   public function group_elements():BelongsToMany
   {
       return $this->belongsToMany(GroupElement::class)->withTimestamps();
   }
   
   public function positions():BelongsToMany
    {
        $relation = $this->belongsToMany(Position::class)->withPivot('quantity');
        if (Schema::hasColumn('element_position', 'is_optional')) {
            $relation->withPivot('is_optional');
        }

        return $relation->withTimestamps();
    }
}
