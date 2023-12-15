<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MaterialPiece extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_in',
        'price_out',
    ];

    public function materials():BelongsToMany
    {
        return $this->belongsToMany(Material::class)->withTimestamps();
    }
}
