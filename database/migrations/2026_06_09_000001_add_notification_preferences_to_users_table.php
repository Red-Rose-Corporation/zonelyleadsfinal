<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_new_lead')->default(true)->after('agreed_terms_at');
            $table->boolean('notify_payment')->default(true)->after('notify_new_lead');
            $table->boolean('notify_review')->default(true)->after('notify_payment');
            $table->boolean('notify_booking')->default(true)->after('notify_review');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_new_lead', 'notify_payment', 'notify_review', 'notify_booking']);
        });
    }
};
