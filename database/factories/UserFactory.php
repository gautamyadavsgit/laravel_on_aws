<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'middile_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'suffix' => fake()->optional(0.2)->randomElement(['Jr.', 'Sr.', 'III']),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'verification_link' => 'https://gautamrei.com/verify/'.Str::random(32),
            'verification_token' => Str::random(60),
            'company_name' => fake()->optional(0.6)->company(),
            'phone' => (int) fake()->numerify('1800######'),
            'alternate_phone' => (int) fake()->numerify('1800######'),
            'hear_about_us' => fake()->numberBetween(1, 5),
            'experiance_level' => fake()->numberBetween(1, 4),
            'investing_reason' => fake()->numberBetween(1, 4),
            'investment_sources' => fake()->numberBetween(1, 4),
            'investing_timeline' => fake()->numberBetween(1, 3),
            'investment_goals' => fake()->numberBetween(1, 4),
            'investment_timelength' => fake()->numberBetween(1, 4),
            'accreditation_status' => fake()->numberBetween(1, 4),
            'users_net_worth' => fake()->numberBetween(1, 6),
            'address' => fake()->address(),
            'phone_verified' => 1,
            'app_connected' => 1,
            'dob' => fake()->date('Y-m-d', '-25 years'),
            'social_security_number' => 'XXX-XX-'.fake()->numerify('####'),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
