<?php

namespace Database\Factories;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Organization>
 */
class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->optional()->e164PhoneNumber(),
            'address' => fake()->optional()->address(),
            'website' => fake()->optional()->url(),
            'field' => fake()->optional()->word(),
            'photo' => null,
            'description' => fake()->optional()->paragraphs(2, true),
        ];
    }
}
