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
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('type', 255)->index();
            $table->unsignedSmallInteger('bedrooms')->default(0)->index();
            $table->unsignedSmallInteger('baths')->default(0)->index();
            $table->unsignedSmallInteger('half_baths')->default(0);
            $table->unsignedSmallInteger('sleeps')->default(0);
            $table->unsignedSmallInteger('garages')->default(0);
            $table->unsignedInteger('square_feets')->default(0)->index();
            $table->string('stories', 255)->nullable();
            $table->unsignedSmallInteger('units')->default(1);
            $table->decimal('lot_size', 8, 2)->default(0);
            $table->unsignedSmallInteger('year_built')->nullable()->index();
            $table->string('zoning', 255)->nullable();
            $table->unsignedBigInteger('value')->default(0)->index();
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
