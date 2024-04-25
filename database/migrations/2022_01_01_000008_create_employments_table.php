<?php

use Dicibi\Orgs\Models\Pivot\Employment;
use Dicibi\Orgs\Models\Pivot\Position;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This table define the employment of a user, it's define
 * the office, title and job function.
 *
 * Output 1: User job position and function declared.
 * | -----------------------------------------------------------------------------------------|
 * |                                                                                          |
 * |   |-------------|       |-------------|                                                  |
 * |   |     User    |  -->  |   Office    | --> Office Level Structure                       |
 * |   |-------------|  |    |-------------|                                                  |
 * |                    |                                                                     |
 * |                    |    |-------------|     | | --> Job Family Listing                   |
 * |                    -->  |   Title     | --> | |                                          |
 * |                         |-------------|     | | --> Organization Level Structure         |
 * |                                                                                          |
 * |------------------------------------------------------------------------------------------|
 */
return new class() extends Migration {
    public function up(): void
    {
        Schema::create((new Employment())->getTable(), static function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('position_id')->constrained((new Position())->getTable());
            $table->foreignId('user_id')->constrained(config('orgs.user'));

            // override job function/description if needed for this specific user
            $table->text('job_function')->nullable();
            $table->text('job_description')->nullable();
            $table->text('note')->nullable();

            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists((new Employment())->getTable());
    }
};
