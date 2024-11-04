<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        ##

        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->cascadeOnDelete();
            $table->string('title', 500);#
            $table->string('slug', 550);
            $table->string('image', 500)->nullable();
            $table->string('original_name', 500)->nullable();
            $table->unsignedSmallInteger('number_rooms');
            $table->unsignedSmallInteger('number_beds');
            $table->unsignedSmallInteger('number_bathrooms');
            $table->unsignedInteger('square_meters');
            $table->boolean('is_visible')->default(true);
            $table->string('address');
            $table->double('lat', 7, 5);
            $table->double('lon', 7, 5);
            $table->softDeletes();
            $table->timestamps();

        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
