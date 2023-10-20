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
        'create_date',
        'validity',
        'client_sign',
        'finish_date',
        'object',
        'city',
        'service',
        'payment_conditions',
        'client_id',
        'difficulty',
        'material',
        'labor_price',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
   
    public function positions():BelongsToMany
   {
       return $this->belongsToMany(Position::class)->withTimestamps();
   }
   
}
