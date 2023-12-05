<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'price_in',
        'price_out',
        'z_schlosserei',
        'z_pe',
        'z_montage',
        'z_fermacell',
        'z_total',
        'zeit_cost',
        'total',
    ];
    public function material_pieces():BelongsToMany
    {
        return $this->belongsToMany(MaterialPiece::class)->withTimestamps();
    }

    public function elements():BelongsToMany
    {
        return $this->belongsToMany(Element::class)->withPivot('quantity')->withTimestamps();
    }
}
