<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Element extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

   public function materials():BelongsToMany
   {
       return $this->belongsToMany(Material::class);
   }

   public function group_elements():BelongsToMany
   {
       return $this->belongsToMany(GroupElement::class);
   }
   
}
