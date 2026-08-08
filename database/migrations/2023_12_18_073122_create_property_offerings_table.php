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
        Schema::create('property_offerings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->unsignedBigInteger('offering_purchase')->default(0);
            $table->unsignedBigInteger('offering_build_cost')->default(0);
            $table->unsignedBigInteger('offering_improvements')->default(0);
            $table->unsignedBigInteger('offering_closing_cost')->default(0);
            $table->unsignedBigInteger('offering_sourcing_fees')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_offerings');
    }
};
