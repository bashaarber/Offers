<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class GroupElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function elements():BelongsToMany
    {
        return $this->belongsToMany(Element::class);
    }
}
