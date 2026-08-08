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
        Schema::table('users', function (Blueprint $table) {
            $table->string('middile_name')->nullable();
            $table->string('last_name')->nullable()->index();
            $table->string('suffix', 20)->nullable();
            $table->string('verification_link')->nullable();
            $table->string('verification_token')->nullable()->index();
            $table->string('company_name')->nullable();
            $table->string('alternate_phone', 30)->nullable();

            $table->foreignId('hear_about_us')->nullable()->constrained('hear_about_us')->nullOnDelete();
            $table->foreignId('experiance_level')->nullable()->constrained('experiance_level')->nullOnDelete();
            $table->foreignId('investing_reason')->nullable()->constrained('reason_for_investing')->nullOnDelete();
            $table->foreignId('investment_sources')->nullable()->constrained('investment_sources')->nullOnDelete();
            $table->foreignId('investing_timeline')->nullable()->constrained('investment_timeline')->nullOnDelete();
            $table->foreignId('investment_goals')->nullable()->constrained('investment_goals')->nullOnDelete();
            $table->foreignId('investment_timelength')->nullable()->constrained('investment_timelength')->nullOnDelete();
            $table->foreignId('accreditation_status')->nullable()->constrained('accreditation_status')->nullOnDelete();
            $table->foreignId('users_net_worth')->nullable()->constrained('users_net_worth')->nullOnDelete();

            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable()->index();
            $table->boolean('phone_verified')->default(false)->comment('Whether phone number is verified');
            $table->boolean('app_connected')->default(false)->comment('Whether user has connected their app');
            $table->date('dob')->nullable()->index();
            $table->string('social_security_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign keys first
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
        });
    }
};
