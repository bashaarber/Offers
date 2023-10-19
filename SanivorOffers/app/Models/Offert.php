<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offert extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_sign',
        'status',
        'validity',
        'client_sign',
        'object',
        'city',
        'service',
        'payment_conditions',
        'client_id',
        'difficulty',
        'material',
        'labor_price',
        'user_id',
        // 'coefficient_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function clients()
    {
        return $this->belongsTo(Client::class);
    }

    public function coefficients()
    {
        return $this->belongsTo(Coefficient::class);
    }
   
    public function positions():BelongsToMany
   {
       return $this->belongsToMany(Position::class)->withTimestamps();
   }
   
}
