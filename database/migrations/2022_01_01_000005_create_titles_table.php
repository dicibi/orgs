<?php

use Dicibi\Orgs\Models\Job\Family;
use Dicibi\Orgs\Models\Job\Title;
use Dicibi\Orgs\Models\Structure;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

/**
 *
 * Output 1: This is Structured Job Title,
 * an Organization-Level Structure. (Job Structural)
 * | --------------------------------------------|
 * |           |-------------|                   |
 * |           |   Title A   |     Structure A   |
 * |           |-------------|                   |
 * |               |    |                        |
 * |        |------|    |--------|               |
 * |        |                    |               |
 * |  |-------------|      |-------------|       |
 * |  |   Title B   |      |   Title C   |       |
 * |  |-------------|      |-------------|       |
 * |---------------------------------------------|
 *
 * Output 2: This is list of Job Titles within Job Family,
 * it doesn't have to be a hierarchy.
 * | --------------------------------------------|
 * |      Family A           Family B            |
 * |  |-------------|     |-------------|        |
 * |  |   Title A   |     |   Title D   |        |
 * |  |-------------|     |-------------|        |
 * |  |-------------|      |-------------|       |
 * |  |   Title B   |      |   Title C   |       |
 * |  |-------------|      |-------------|       |
 * |---------------------------------------------|
 *
 * This schema not includes Job Function, yet.
 *
 */
return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new Title())->getTable(), static function (Blueprint $table) {
            $table->id();

            NestedSet::columns($table);

            $table->foreignId('structure_id')->constrained((new Structure())->getTable());
            $table->foreignId('job_family_id')->nullable()->constrained((new Family())->getTable())->nullOnDelete();

            $table->string('name');
            $table->text('description')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Title())->getTable());
    }
};
