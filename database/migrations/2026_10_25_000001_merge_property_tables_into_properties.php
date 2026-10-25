<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fold address, details and offering into `properties`; drop documents and search logs.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('properties', 'address_1')) {
            Schema::table('properties', function (Blueprint $table) {
                $table->string('address_1', 255)->nullable();
                $table->string('address_2', 255)->nullable();
                $table->string('city', 112)->nullable()->index();
                $table->string('state', 112)->nullable()->index();
                $table->string('zip', 20)->nullable()->index();

                $table->string('type', 255)->nullable()->index();
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

                $table->unsignedBigInteger('offering_purchase')->default(0);
                $table->unsignedBigInteger('offering_build_cost')->default(0);
                $table->unsignedBigInteger('offering_improvements')->default(0);
                $table->unsignedBigInteger('offering_closing_cost')->default(0);
                $table->unsignedBigInteger('offering_sourcing_fees')->default(0);
            });
        }

        DB::statement('UPDATE properties p JOIN property_address a ON a.property_id = p.id
            SET p.address_1 = a.address_1, p.address_2 = a.address_2, p.city = a.city, p.state = a.state, p.zip = a.zip');

        DB::statement('UPDATE properties p JOIN property_details d ON d.property_id = p.id
            SET p.type = d.type, p.bedrooms = d.bedrooms, p.baths = d.baths, p.half_baths = d.half_baths,
                p.sleeps = d.sleeps, p.garages = d.garages, p.square_feets = d.square_feets, p.stories = d.stories,
                p.units = d.units, p.lot_size = d.lot_size, p.year_built = d.year_built, p.zoning = d.zoning, p.value = d.value');

        DB::statement('UPDATE properties p JOIN property_offerings o ON o.property_id = p.id
            SET p.offering_purchase = o.offering_purchase, p.offering_build_cost = o.offering_build_cost,
                p.offering_improvements = o.offering_improvements, p.offering_closing_cost = o.offering_closing_cost,
                p.offering_sourcing_fees = o.offering_sourcing_fees');

        Schema::dropIfExists('property_address');
        Schema::dropIfExists('property_details');
        Schema::dropIfExists('property_offerings');
        Schema::dropIfExists('properties_document');
        Schema::dropIfExists('user_search_logs');
    }

    /**
     * Structure is restored; data in the dropped tables (documents and search log rows) is not.
     */
    public function down(): void
    {
        Schema::create('property_address', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('address_1', 255);
            $table->string('address_2', 255)->nullable();
            $table->string('city', 112)->index();
            $table->string('state', 112)->index();
            $table->string('zip', 20)->index();
            $table->timestamps();
        });

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

        DB::statement('INSERT INTO property_address (property_id, address_1, address_2, city, state, zip, created_at, updated_at)
            SELECT id, COALESCE(address_1, ""), address_2, COALESCE(city, ""), COALESCE(state, ""), COALESCE(zip, ""), NOW(), NOW() FROM properties');
        DB::statement('INSERT INTO property_details (property_id, type, bedrooms, baths, half_baths, sleeps, garages, square_feets, stories, units, lot_size, year_built, zoning, value, created_at, updated_at)
            SELECT id, COALESCE(type, ""), bedrooms, baths, half_baths, sleeps, garages, square_feets, stories, units, lot_size, year_built, zoning, value, NOW(), NOW() FROM properties');
        DB::statement('INSERT INTO property_offerings (property_id, offering_purchase, offering_build_cost, offering_improvements, offering_closing_cost, offering_sourcing_fees, created_at, updated_at)
            SELECT id, offering_purchase, offering_build_cost, offering_improvements, offering_closing_cost, offering_sourcing_fees, NOW(), NOW() FROM properties');

        Schema::create('properties_document', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->string('document_key');
            $table->string('document_value');
            $table->timestamps();
        });

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
            $table->string('sort_by', 50)->nullable();
            $table->json('filters_payload')->nullable();
            $table->unsignedInteger('results_count')->default(0);
            $table->timestamps();
        });

        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'address_1', 'address_2', 'city', 'state', 'zip',
                'type', 'bedrooms', 'baths', 'half_baths', 'sleeps', 'garages', 'square_feets', 'stories',
                'units', 'lot_size', 'year_built', 'zoning', 'value',
                'offering_purchase', 'offering_build_cost', 'offering_improvements', 'offering_closing_cost', 'offering_sourcing_fees',
            ]);
        });
    }
};
