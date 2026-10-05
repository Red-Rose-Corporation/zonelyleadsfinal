<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Idempotent: this was blocked on production by an earlier failing migration
        // and some of these columns/indexes may already exist there.
        if (!Schema::hasColumn('hunt_leads', 'email')) {
            Schema::table('hunt_leads', function (Blueprint $table) {
                $table->string('email')->nullable()->after('phone');
            });
        }
        if (!Schema::hasColumn('hunt_leads', 'opted_out')) {
            Schema::table('hunt_leads', function (Blueprint $table) {
                $table->boolean('opted_out')->default(false)->after('status');
            });
        }
        if (!Schema::hasColumn('hunt_leads', 'email_sent_at')) {
            Schema::table('hunt_leads', function (Blueprint $table) {
                $table->timestamp('email_sent_at')->nullable()->after('sms_sent_at');
            });
        }

        foreach (['phone', 'email'] as $column) {
            $this->addIndexIfMissing('hunt_leads', $column);
        }
    }

    public function down(): void
    {
        Schema::table('hunt_leads', function (Blueprint $table) {
            $table->dropIndex(['phone']);
            $table->dropIndex(['email']);
            $table->dropColumn(['email', 'opted_out', 'email_sent_at']);
        });
    }

    private function addIndexIfMissing(string $table, string $column): void
    {
        try {
            Schema::table($table, function (Blueprint $t) use ($column) {
                $t->index($column);
            });
        } catch (QueryException $e) {
            $duplicate = ($e->errorInfo[1] ?? null) == 1061
                || str_contains($e->getMessage(), 'Duplicate key name')
                || str_contains($e->getMessage(), 'already exists');
            if (!$duplicate) {
                throw $e;
            }
        }
    }
};
