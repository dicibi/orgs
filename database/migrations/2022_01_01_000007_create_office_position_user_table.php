<?php

use Dicibi\Orgs\Models\Pivot\OfficePosition;
use Dicibi\Orgs\Models\Pivot\OfficePositionUser;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new OfficePositionUser())->getTable(), function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('office_position_id')->constrained((new OfficePosition())->getTable());
            $table->foreignIdFor(new (config('orgs.user')), 'user_id')->constrained((new (config('orgs.user')))->getTable());
            $table->string('office_occupation')->nullable(); // Customize office position name if more than 1 position in 1 office structure

            $table->text('note')->nullable();

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(false); // is_active is used for office position to determine required employees

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('orgs.prefix') . 'office_position_user');
    }
};
