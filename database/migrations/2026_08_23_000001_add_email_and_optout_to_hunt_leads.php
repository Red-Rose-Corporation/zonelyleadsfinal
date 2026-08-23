<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hunt_leads', function (Blueprint $table) {
            $table->string('email')->nullable()->after('phone');
            $table->boolean('opted_out')->default(false)->after('status');
            $table->timestamp('email_sent_at')->nullable()->after('sms_sent_at');
            $table->index('phone');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('hunt_leads', function (Blueprint $table) {
            $table->dropIndex(['phone']);
            $table->dropIndex(['email']);
            $table->dropColumn(['email', 'opted_out', 'email_sent_at']);
        });
    }
};
