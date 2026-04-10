<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coefficient extends Model
{
    use HasFactory;

    protected $fillable = [
        'validity',
        'labor_cost',
        'labor_price',
        'service',
        'material',
        'difficulty',
        'payment_conditions',
        'default_rabatt',
        'default_signature',
    ];
}
