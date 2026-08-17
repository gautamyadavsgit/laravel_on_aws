<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds for sample investors.
     */
    public function run(): void
    {
        $users = [
            [
                'first_name' => 'Alexander',
                'middile_name' => 'James',
                'last_name' => 'Wright',
                'suffix' => 'Jr.',
                'email' => 'alexander.wright@example.com',
                'password' => Hash::make('password123'),
                'verification_link' => 'https://gautamrei.com/verify/'.Str::random(32),
                'verification_token' => Str::random(60),
                'company_name' => 'Wright Capital Investments LLC',
                'phone' => 1800555123,
                'alternate_phone' => 1800555124,
                'hear_about_us' => 1,
                'experiance_level' => 4, // Accredited
                'investing_reason' => 1,
                'investment_sources' => 1,
                'investing_timeline' => 1,
                'investment_goals' => 1,
                'investment_timelength' => 3,
                'accreditation_status' => 1,
                'users_net_worth' => 5,
                'address' => '742 Evergreen Terrace, Gatlinburg, TN 37738',
                'phone_verified' => 1,
                'app_connected' => 1,
                'dob' => '1984-06-15',
                'social_security_number' => 'XXX-XX-4892',
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Sophia',
                'middile_name' => 'Marie',
                'last_name' => 'Chen',
                'suffix' => null,
                'email' => 'sophia.chen@example.com',
                'password' => Hash::make('password123'),
                'verification_link' => 'https://gautamrei.com/verify/'.Str::random(32),
                'verification_token' => Str::random(60),
                'company_name' => 'Blue Ridge Holdings',
                'phone' => 1800555234,
                'alternate_phone' => null,
                'hear_about_us' => 2,
                'experiance_level' => 2,
                'investing_reason' => 2,
                'investment_sources' => 2,
                'investing_timeline' => 1,
                'investment_goals' => 2,
                'investment_timelength' => 2,
                'accreditation_status' => 2,
                'users_net_worth' => 4,
                'address' => '104 Mountain Vista Way, Asheville, NC 28801',
                'phone_verified' => 1,
                'app_connected' => 1,
                'dob' => '1990-11-22',
                'social_security_number' => 'XXX-XX-1145',
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Marcus',
                'middile_name' => 'David',
                'last_name' => 'Sterling',
                'suffix' => 'III',
                'email' => 'marcus.sterling@example.com',
                'password' => Hash::make('password123'),
                'verification_link' => 'https://gautamrei.com/verify/'.Str::random(32),
                'verification_token' => Str::random(60),
                'company_name' => 'Sterling Family Trust',
                'phone' => 1800555345,
                'alternate_phone' => 1800555346,
                'hear_about_us' => 3,
                'experiance_level' => 3,
                'investing_reason' => 3,
                'investment_sources' => 3,
                'investing_timeline' => 2,
                'investment_goals' => 3,
                'investment_timelength' => 4,
                'accreditation_status' => 3,
                'users_net_worth' => 6,
                'address' => '500 Pelican Bay Blvd, Destin, FL 32541',
                'phone_verified' => 1,
                'app_connected' => 1,
                'dob' => '1978-03-08',
                'social_security_number' => 'XXX-XX-7821',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            $user = DB::table('users')->where('email', $userData['email'])->first();

            if ($user) {
                DB::table('users')->where('id', $user->id)->update($userData);
                $userId = $user->id;
            } else {
                $userId = DB::table('users')->insertGetId(array_merge($userData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }

            // User Beneficiary
            DB::table('user_beneficiary')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'beneficiary_name' => $userData['first_name'].' Family Trust',
                    'beneficiary_phone' => 1800555999,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // User Notification Alerts
            DB::table('user_alerts')->updateOrInsert(
                ['user_id' => $userId],
                [
                    'new_deals' => 1,
                    'system_notice' => 1,
                    'emails' => 1,
                    'sms' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
