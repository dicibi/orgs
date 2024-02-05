<?php

use Dicibi\Orgs\Models\Job\Clan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new Clan())->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Clan())->getTable());
    }
};
