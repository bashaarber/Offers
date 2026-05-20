<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ListFilter
{
    /**
     * Apply per-column "contains" filters from the request to an Eloquent query.
     *
     * $map is keyed by the filter field name (matches the `name="f[<key>]"` input)
     * and maps to either:
     *   - a string column name (filtered on the base table)
     *   - ['relation' => 'rel', 'column' => 'col'] for a whereHas filter
     *   - ['raw' => fn($q, $value) => ...] for custom logic
     */
    public static function apply(Builder $query, Request $request, array $map): Builder
    {
        $filters = (array) $request->input('f', []);

        foreach ($map as $key => $config) {
            $value = isset($filters[$key]) ? trim((string) $filters[$key]) : '';
            if ($value === '') {
                continue;
            }

            if (is_string($config)) {
                self::applyLike($query, $config, $value);
            } elseif (is_array($config) && isset($config['raw']) && is_callable($config['raw'])) {
                $config['raw']($query, $value);
            } elseif (is_array($config) && isset($config['relation'], $config['column'])) {
                $relation = $config['relation'];
                $column = $config['column'];
                $query->whereHas($relation, function ($q) use ($column, $value) {
                    self::applyLike($q, $column, $value);
                });
            }
        }

        return $query;
    }

    /**
     * Case-insensitive contains-match that works across sqlite/mysql/pgsql,
     * including numeric and date columns (cast to text first).
     */
    public static function applyLike($query, string $column, string $value): void
    {
        $needle = '%' . mb_strtolower($value) . '%';
        $query->whereRaw('LOWER(CAST(' . $column . ' AS TEXT)) LIKE ?', [$needle]);
    }
}
