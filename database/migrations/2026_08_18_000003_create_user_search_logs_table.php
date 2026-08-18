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
        Schema::create('user_search_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id', 100)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('keyword', 255)->nullable()->index();
            $table->string('location', 255)->nullable()->index();
            $table->unsignedBigInteger('min_price')->nullable()->index();
            $table->unsignedBigInteger('max_price')->nullable()->index();
            $table->string('property_type', 100)->nullable()->index();
            $table->unsignedSmallInteger('bedrooms')->nullable();
            $table->unsignedSmallInteger('bathrooms')->nullable();
            $table->decimal('min_cap_rate', 5, 2)->nullable();
            $table->boolean('is_1031')->nullable();
            $table->string('sort_by', 50)->nullable();
            $table->json('filters_payload')->nullable();
            $table->unsignedInteger('results_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_search_logs');
    }
};
