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
        Schema::table('users', function (Blueprint $table) {
            $table->string('middile_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('verification_link');
            $table->string('verification_token');
            $table->string('company_name')->nullable();
            $table->integer('alternate_phone')->nullable();
            $table->unsignedBigInteger('hear_about_us')->nullable();
            $table->foreign('hear_about_us')->references('id')->on('hear_about_us');
            $table->unsignedBigInteger('experiance_level')->nullable();
            $table->foreign('experiance_level')->references('id')->on('experiance_level');
            $table->unsignedBigInteger('investing_reason')->nullable();
            $table->foreign('investing_reason')->references('id')->on('reason_for_investing');
            $table->unsignedBigInteger('investment_sources')->nullable();
            $table->foreign('investment_sources')->references('id')->on('investment_sources');
            $table->unsignedBigInteger('investing_timeline')->nullable();
            $table->foreign('investing_timeline')->references('id')->on('investment_timeline');
            $table->unsignedBigInteger('investment_goals')->nullable();
            $table->foreign('investment_goals')->references('id')->on('investment_goals');
            $table->unsignedBigInteger('investment_timelength')->nullable();
            $table->foreign('investment_timelength')->references('id')->on('investment_timelength');
            $table->unsignedBigInteger('accreditation_status')->nullable();
            $table->foreign('accreditation_status')->references('id')->on('accreditation_status');
            $table->unsignedBigInteger('users_net_worth')->nullable();
            $table->foreign('users_net_worth')->references('id')->on('users_net_worth');
            $table->text('address')->nullable();
            $table->integer('phone')->nullable();
            $table->tinyInteger('phone_verified')->default('0')->comment('0 for not verified,1 for verified');
            $table->tinyInteger('app_connected')->default('0')->comment('0 for not connected,1 for not connected');
            $table->date('dob')->nullable();
            $table->string('social_security_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('first_name', 'name'); // Revert column rename
            $table->dropForeign(['hear_about_us']);
            $table->dropForeign(['experiance_level']);
            $table->dropForeign(['investing_reason']);
            $table->dropForeign(['investment_sources']);
            $table->dropForeign(['investing_timeline']);
            $table->dropForeign(['investment_goals']);
            $table->dropForeign(['investment_timelength']);
            $table->dropForeign(['accreditation_status']);
            $table->dropForeign(['users_net_worth']);
            // Drop added columns
            $table->dropColumn([
                'middile_name',
                'last_name',
                'suffix',
                'verification_link',
                'verification_token',
                'company_name',
                'alternate_phone',
                'hear_about_us',
                'experiance_level',
                'investing_reason',
                'investment_sources',
                'investing_timeline',
                'investment_goals',
                'investment_timelength',
                'accreditation_status',
                'users_net_worth',
                'address',
                'phone',
                'phone_verified',
                'app_connected',
                'dob',
                'social_security_number',
            ]);

            // Drop foreign keys

        });
    }
};
