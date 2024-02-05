<?php

use Dicibi\Orgs\Models\Job\Clan;
use Dicibi\Orgs\Models\Job\Family;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new Family())->getTable(), static function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_clan_id')->constrained((new Clan())->getTable())->cascadeOnDelete();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Family())->getTable());
    }
};
