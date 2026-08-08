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
        Schema::create('user_alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->tinyInteger('new_deals')->default('0')->comment('0 for no,1 for yes');
            $table->tinyInteger('system_notice')->default('0')->comment('0 for no,1 for yes');
            $table->tinyInteger('emails')->default('0')->comment('0 for no,1 for yes');
            $table->tinyInteger('sms')->default('0')->comment('0 for no,1 for yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_alerts');
    }
};
