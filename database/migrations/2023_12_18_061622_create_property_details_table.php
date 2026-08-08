<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->string('type', 255);
            $table->integer('bedrooms');
            $table->integer('baths');
            $table->integer('half_baths');
            $table->integer('sleeps');
            $table->integer('garages');
            $table->integer('square_feets');
            $table->longText('stories');
            $table->integer('units');
            $table->integer('lot_size');
            $table->integer('year_built');
            $table->string('zoning', 255);
            $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_details');
    }
};
