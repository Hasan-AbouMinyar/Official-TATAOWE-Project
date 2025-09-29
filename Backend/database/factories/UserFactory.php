<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'phoneNumber' => fake()->unique()->e164PhoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'photo' => null,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}

// <?php
// $user = App\Models\User::create([
//     'name' => 'Demo User',
//     'username' => 'demo',
//     'phoneNumber' => '218912345678',
//     'email' => 'demo@example.com',
//     'photo' => null,
//     'password' => bcrypt('password123'),
// ]);


// php artisan tinker

// <?php
// $user = App\Models\User::create([
//     'name' => 'Demo User',
//     'username' => 'demo',
//     'phoneNumber' => '218912345678',
//     'email' => 'demo@example.com',
//     'photo' => null,
//     'password' => bcrypt('password123'),
// ]);