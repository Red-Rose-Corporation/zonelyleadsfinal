<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Optional photos for a service (up to Service::MAX_PHOTOS). Nothing reads this table until the
 * photo UI ships, so creating it changes no existing page.
 *
 * Written defensively because production's schema has drifted from the migrations before
 * (a hand-made index once made `migrate` fail on every deploy and blocked later migrations):
 *  - does nothing if the table already exists;
 *  - creates the table first, then tries the foreign key and ignores a failure, so a type
 *    mismatch can never abort the migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('service_photos')) {
            return;
        }

        Schema::create('service_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('service_id');
            $table->string('path', 500);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['service_id', 'sort_order']);
        });

        if (Schema::hasTable('services')) {
            try {
                Schema::table('service_photos', function (Blueprint $table) {
                    $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete();
                });
            } catch (\Throwable $e) {
                // Integrity is nice to have, not required: the Service model also deletes its photos.
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_photos');
    }
};
