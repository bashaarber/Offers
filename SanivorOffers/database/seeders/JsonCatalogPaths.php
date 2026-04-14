<?php

namespace Database\Seeders;

/**
 * Single source of truth for locating the catalog JSON file across seeders and commands.
 */
class JsonCatalogPaths
{
    /**
     * @return list<string|null>
     */
    public static function candidateFilePaths(): array
    {
        return [
            env('JSON_IMPORT_PATH'),
            base_path('database/seeders/DB___proj_98_2026-01-19 18_03_10.json'),
            storage_path('app/DB___proj_98_2026-01-19 18_03_10.json'),
            '/Users/arberbasha/Downloads/DB___proj_98_2026-01-19 18_03_10.json',
            '/Users/arberbasha/Documents/DB___proj_98_2026-01-19 18_03_10.json',
        ];
    }
}
