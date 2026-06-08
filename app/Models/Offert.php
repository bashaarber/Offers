<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offert extends Model
{
    use HasFactory;

    public const DISPLAY_NUMBER_OFFSET = 599;
    public const DISPLAY_NUMBER_SUFFIX = '-H';
    public const SUB_DISPLAY_NUMBER_SUFFIX = '-S';

    protected $fillable = [
        'parent_id',
        'is_gross',
        'teil_objekt',
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
        'client_address',
        'client_address_2',
        'client_address_3',
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
        'is_gross'  => 'boolean',
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

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function subOfferts(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function isSubOffert(): bool
    {
        return !empty($this->parent_id);
    }

    public function isGross(): bool
    {
        return (bool) $this->is_gross;
    }

    /**
     * 1-based position of this child among its siblings (ordered by id).
     * Used to build the {parent}-{n} display number (e.g. 633-H-2).
     */
    public function childIndex(): int
    {
        if (!$this->isSubOffert()) {
            return 0;
        }

        return static::where('parent_id', $this->parent_id)
            ->where('id', '<=', $this->id)
            ->count();
    }

    public function positions():BelongsToMany
   {
       return $this->belongsToMany(Position::class)->withTimestamps();
   }

    public static function formatDisplayNumber(int $offertId, string $suffix = self::DISPLAY_NUMBER_SUFFIX): string
    {
        return ($offertId + self::DISPLAY_NUMBER_OFFSET) . $suffix;
    }

    public function getDisplayNumberAttribute(): string
    {
        // Child offers extend the parent's running number with their sibling index,
        // e.g. parent 633-H -> children 633-H-1, 633-H-2, ...
        if ($this->isSubOffert()) {
            return self::formatDisplayNumber((int) $this->parent_id) . '-' . $this->childIndex();
        }

        return self::formatDisplayNumber((int) $this->id);
    }

}
