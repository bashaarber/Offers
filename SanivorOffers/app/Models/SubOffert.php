<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Sub Offert — a fully standalone document module, parallel to Offert but with
 * its own table, numbering and navigation. Numbers use the -S suffix on the
 * record's OWN id sequence (first sub offert = 600-S). A nested sub-of-sub
 * shares its root ancestor's number (they are told apart by client).
 */
class SubOffert extends Model
{
    use HasFactory;

    public const DISPLAY_NUMBER_OFFSET = 599;
    public const DISPLAY_NUMBER_SUFFIX = '-S';

    protected $fillable = [
        'parent_id',
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

    /** Positions belong to a sub-offert via positions.sub_offert_id (shared editor). */
    public function positions(): HasMany
    {
        return $this->hasMany(Position::class, 'sub_offert_id');
    }

    public static function formatDisplayNumber(int $id, string $suffix = self::DISPLAY_NUMBER_SUFFIX): string
    {
        return ($id + self::DISPLAY_NUMBER_OFFSET) . $suffix;
    }

    /**
     * Walk up the parent chain so nested sub-offerts share the root's running
     * number. Falls back to the record's own id when it is a top-level record.
     */
    public function rootId(): int
    {
        $node = $this;
        $guard = 0;
        while (!empty($node->parent_id) && $guard < 20) {
            $next = $node->relationLoaded('parent') ? $node->parent : self::find($node->parent_id);
            if (! $next) {
                break;
            }
            $node = $next;
            $guard++;
        }

        return (int) ($node->id ?? $this->id);
    }

    public function getDisplayNumberAttribute(): string
    {
        return self::formatDisplayNumber($this->rootId());
    }
}
