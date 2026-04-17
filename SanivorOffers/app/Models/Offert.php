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
        'default_rabatt',
        'user_id',
        'locked_by',
        'locked_at',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lockingUser()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    /** True when another user holds a non-expired lock (expires after 2 minutes). */
    public function isLockedByOther(): bool
    {
        if (! $this->locked_by || ! $this->locked_at) {
            return false;
        }
        if ($this->locked_at->lt(now()->subMinutes(2))) {
            return false;
        }

        return (int) $this->locked_by !== (int) auth()->id();
    }

    public function acquireLock(): void
    {
        $this->update(['locked_by' => auth()->id(), 'locked_at' => now()]);
    }

    public function releaseLock(): void
    {
        if ((int) $this->locked_by === (int) auth()->id()) {
            $this->update(['locked_by' => null, 'locked_at' => null]);
        }
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
