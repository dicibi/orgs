<?php

use Dicibi\Orgs\Models\Job\Title;
use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Pivot\Position;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 *
 * This table is pivot of office + title, which showing
 * open positions within an office.
 *
 * Output 1: Office-level structure, available positions.
 * | --------------------------------------------|
 * |           |-----pos-----|                   |
 * |           |   Title  A  |      Office A     |
 * |           |-------------|                   |
 * |               |    |                        |
 * |        |------|    |--------|               |
 * |        |                    |               |
 * |  |-----pos-----|      |-----pos-----|       |
 * |  |   Title  B  |      |   Title  C  |       |
 * |  |-------------|      |-------------|       |
 * |---------------------------------------------|
 *
 */
return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new Position())->getTable(), static function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignId('office_id')->constrained((new Office())->getTable());
            $table->foreignId('title_id')->constrained((new Title())->getTable());

            // number of availability in this position
            $table->unsignedMediumInteger('quota')->default(1);
            $table->text('job_function')->nullable();
            $table->text('job_description')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            // within the same office, there cannot be the same title within it.
            $table->unique(['office_id', 'title_id'], 'UNQ_office_title');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Position())->getTable());
    }
};
