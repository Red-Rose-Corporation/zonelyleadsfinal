<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Production already has some of these indexes (added by hand), which made
        // this migration fail with "Duplicate key name" on every deploy and block
        // every later migration. Add each index on its own and skip ones that exist.
        foreach (['seller_id', 'status', 'email', 'created_at'] as $column) {
            $this->addIndexIfMissing('leads', $column);
        }
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['seller_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['email']);
            $table->dropIndex(['created_at']);
        });
    }

    private function addIndexIfMissing(string $table, string $column): void
    {
        try {
            Schema::table($table, function (Blueprint $t) use ($column) {
                $t->index($column);
            });
        } catch (QueryException $e) {
            $duplicate = ($e->errorInfo[1] ?? null) == 1061          // MySQL: duplicate key name
                || str_contains($e->getMessage(), 'Duplicate key name')
                || str_contains($e->getMessage(), 'already exists'); // SQLite
            if (!$duplicate) {
                throw $e;
            }
        }
    }
};
