<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
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
       if (self::elementMaterialHasSortOrder()) {
           $relation->withPivot('sort_order')->orderByPivot('sort_order');
       }

       return $relation->withTimestamps();
   }

   /**
    * Whether element_material has the sort_order column.
    * Cached for 24h — the column never changes after the migration runs —
    * to avoid an information_schema round-trip on every relation load.
    */
   public static function elementMaterialHasSortOrder(): bool
   {
       return Cache::remember('schema_element_material_has_sort_order', 86400, function () {
           return Schema::hasColumn('element_material', 'sort_order');
       });
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
