<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionMaterial extends Model
{
    use HasFactory;

    protected $fillable = ['position_id', 'element_id', 'material_id', 'quantity'];
}
