<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('property_aacf', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->integer('annual_rent_amount');
            $table->integer('annual_rent_gross_yield');
            $table->integer('aacf_expences');
            $table->integer('aacf_net');
            $table->timestamps();


        });
        DB::statement('ALTER TABLE property_aacf COMMENT "This table stores Anticipated Annual Cash Flow"');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_aacf');
    }
};
