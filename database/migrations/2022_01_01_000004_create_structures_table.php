<?php

use Dicibi\Orgs\Models\Structure;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

/**
 * Output: An organization-wide structure that
 * combined from multiple structures.
 * |---------------------------------------|
 * |        |-------------|                |
 * |        | Structure A |                |
 * |        |-------------|                |
 * |            |    |                     |
 * |     |------|    |--------|            |
 * |     |                    |            |
 * | |-------------|      |-------------|  |
 * | | Structure B |      | Structure C |  |
 * | |-------------|      |-------------|  |
 * |---------------------------------------|
 *
 * It's also possible to use only 1 structure
 * to make organization-level structure.
 *
 * Combining multiple structures also possible
 * to make organization-level structure.
 *
 * Maybe it's good approach when we have cases
 * to make multiple divisions, groups, sections etc.
 *
 * @see 'create titles table' migration
 *
 */
return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new Structure())->getTable(), static function (Blueprint $table) {
            $table->id();

            NestedSet::columns($table);

            $table->string('name');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Structure())->getTable());
    }
};
