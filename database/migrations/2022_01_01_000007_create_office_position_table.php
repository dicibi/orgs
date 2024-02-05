<?php

use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Pivot\OfficePosition;
use Dicibi\Orgs\Models\Position;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new OfficePosition())->getTable(), function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('office_id')->constrained((new Office())->getTable());
            $table->foreignId('position_id')->constrained((new Position())->getTable());

            /* this column just for attribute for position_id */
            $table->unsignedMediumInteger('num_employee_needed')->default(1);
            $table->text('note')->nullable(); // to store information about office area position, ex: Marketing Man. Malang/Surabaya/Banyuwangi
            $table->timestamps();

            $table->unique(['office_id', 'position_id'], 'UNQ_office_position');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new OfficePosition())->getTable());
    }
};
