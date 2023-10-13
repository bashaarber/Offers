<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'total'
    ];


}
