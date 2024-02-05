<?php

use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Structure;
use Kalnoy\Nestedset\NestedSet;
use Dicibi\IndoRegion\IndoRegion;
use Dicibi\IndoRegion\Enums\Feature;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new Office())->getTable(), static function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_id')->nullable()->constrained((new Structure())->getTable())->nullOnDelete();

            NestedSet::columns($table);

            $table->string('code');
            $table->string('name');
            $table->string('address')->nullable();

            IndoRegion::tables($table, [
                Feature::Province,
                Feature::Regency,
                Feature::District,
            ]);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Office())->getTable());
    }
};
