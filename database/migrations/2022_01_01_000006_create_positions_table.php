<?php

use Dicibi\Orgs\Models\Job\Family;
use Dicibi\Orgs\Models\Position;
use Dicibi\Orgs\Models\Structure;
use Kalnoy\Nestedset\NestedSet;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new Position())->getTable(), static function (Blueprint $table) {
            $table->id();

            NestedSet::columns($table);

            $table->foreignId('structure_id')->constrained((new Structure())->getTable())->cascadeOnDelete();
            $table->foreignId('job_family_id')->nullable()->constrained((new Family())->getTable())->cascadeOnDelete();

            $table->string('name');
            $table->string('occupation');

            $table->text('job_main_task')->nullable();
            $table->text('job_function')->nullable();
            $table->text('job_responsibility')->nullable();
            $table->text('job_authority')->nullable();

            $table->text('job_qualification')->nullable();
            $table->text('job_experience')->nullable();
            $table->text('job_training')->nullable();
            $table->text('job_capability')->nullable();
            $table->text('job_emotional_quotient')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Position())->getTable());
    }
};
