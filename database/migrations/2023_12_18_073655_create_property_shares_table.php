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
        Schema::create('property_shares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->integer('equity_raise');
            $table->integer('price_per_share_deed');
            $table->integer('total_developer_share_deeds');
            $table->integer('total_raise_share_deeds');
            $table->integer('total_share_deeds');
            $table->date('first_dividend_date');
            $table->date('seccond_dividend_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_shares');
    }
};
