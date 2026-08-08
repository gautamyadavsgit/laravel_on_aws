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
        Schema::create('property_financial_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->integer('management_fee');
            $table->integer('cash_reserve');
            $table->integer('hold_period');
            $table->string('annual_appreciation',255);
            $table->string('aum_fee_1',255);
            $table->string('aum_fee_2',255);
            $table->string('aum_fee_3',255);
            $table->string('average_time_to_rent',255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_financial_details');
    }
};
