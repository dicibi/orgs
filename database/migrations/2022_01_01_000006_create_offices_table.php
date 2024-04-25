<?php

use Dicibi\Orgs\Models\Office;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kalnoy\Nestedset\NestedSet;

/**
 *
 * Output 1: Office-level structure.
 * | --------------------------------------------|
 * |           |-------------|                   |
 * |           |   Office A  |                   |
 * |           |-------------|                   |
 * |               |    |                        |
 * |        |------|    |--------|               |
 * |        |                    |               |
 * |  |-------------|      |-------------|       |
 * |  |   Office B  |      |   Office C  |       |
 * |  |-------------|      |-------------|       |
 * |---------------------------------------------|
 *
 */
return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new Office())->getTable(), static function (Blueprint $table) {
            $table->id();

            NestedSet::columns($table);

            $table->string('name');
            $table->text('address')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Office())->getTable());
    }
};
