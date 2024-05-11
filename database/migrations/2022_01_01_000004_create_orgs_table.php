<?php

use Dicibi\Orgs\Models\Job\Title;
use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Pivot\Position;
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

        Schema::create((new Title())->getTable(), static function (Blueprint $table) {
            $table->id();

            NestedSet::columns($table);

            $table->foreignId('structure_id')->nullable()->constrained((new Structure())->getTable());

            if (!is_null(config('orgs.job_family'))) {
                $jobFamilyClass = config('orgs.job_family');
                $tableName = (new $jobFamilyClass())->getTable();
                $table->foreignId(str($tableName)->singular()->snake() . '_id')->nullable()->constrained()->nullOnDelete();
            }

            if (!is_null(config('orgs.job_clan'))) {
                $jobClanClass = config('orgs.job_clan');
                $tableName = (new $jobClanClass())->getTable();
                $table->foreignId(str($tableName)->singular()->snake() . '_id')->nullable()->constrained()->nullOnDelete();
            }

            $table->string('name');
            $table->text('description')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create((new Office())->getTable(), static function (Blueprint $table) {
            $table->id();

            NestedSet::columns($table);

            $table->string('name');
            $table->text('address')->nullable();

            $table->timestamps();
        });

        Schema::create((new Position())->getTable(), static function (Blueprint $table) {
            $table->uuid('id')->primary();

            NestedSet::columns($table);

            $table->foreignId('title_id')->constrained((new Title())->getTable());
            $table->foreignId('office_id')->constrained((new Office())->getTable());

            // number of availability in this position
            $table->unsignedMediumInteger('quota')->default(1);
            $table->text('job_function')->nullable();
            $table->text('job_description')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Position())->getTable());
        Schema::dropIfExists((new Office())->getTable());
        Schema::dropIfExists((new Title())->getTable());
        Schema::dropIfExists((new Structure())->getTable());
    }
};
