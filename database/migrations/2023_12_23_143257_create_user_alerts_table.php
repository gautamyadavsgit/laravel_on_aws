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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('new_deals')->default(false)->comment('Whether to receive new deal alerts');
            $table->boolean('system_notice')->default(false)->comment('Whether to receive system notices');
            $table->boolean('emails')->default(false)->comment('Whether to receive email notifications');
            $table->boolean('sms')->default(false)->comment('Whether to receive SMS notifications');
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
