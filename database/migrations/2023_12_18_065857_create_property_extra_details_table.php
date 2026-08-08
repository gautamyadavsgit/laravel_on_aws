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
        Schema::create('property_extra_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->string('deed_fraction_1', 255);
            $table->string('deed_fraction_2', 255);
            $table->enum('leveraged', ['0', '1'])->default('0')->comment('0 for no,1 for yes');
            $table->integer('leverage_amount');
            $table->integer('leverage_percent');
            $table->integer('rent_rate');
            $table->integer('market_rent_rate');
            $table->integer('occupancy_rate');
            $table->string('occupancy_status', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_extra_details');
    }
};
