<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public $withinTransaction = false;

    private function safeRun(string $sql): void
    {
        try {
            DB::unprepared($sql);
        } catch (\Throwable $e) {
            Log::warning('add_in_labor_price migration: skipping statement', [
                'error' => $e->getMessage(),
                'sql'   => substr($sql, 0, 200),
            ]);
        }
    }

    public function up(): void
    {
        $this->safeRun('ROLLBACK');
        $this->safeRun('ALTER TABLE coefficients ADD COLUMN IF NOT EXISTS in_labor_price numeric(15,4) NULL');
    }

    public function down(): void
    {
        $this->safeRun('ALTER TABLE coefficients DROP COLUMN IF EXISTS in_labor_price');
    }
};
