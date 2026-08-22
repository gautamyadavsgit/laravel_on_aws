<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = config('auth.admin_cred.email');
        $password = config('auth.admin_cred.password');
        DB::table('admins')->updateOrInsert(
            ['email' => $email],
            [
                'username' => 'gautam',
                'password' => Hash::make($password),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
