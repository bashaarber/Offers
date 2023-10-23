<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Position extends Model
{
    use HasFactory;
    protected $fillable = [
        'price_brutto',
        'price_discount',
        'discount',
        'costo',
        'profit',
        'total',
    ];
   
    public function offerts():BelongsToMany
    {
        return $this->belongsToMany(Offert::class)->withTimestamps();
    }

    public function organigrams():BelongsToMany
    {
        return $this->belongsToMany(Organigram::class)->withTimestamps();
    }
}
