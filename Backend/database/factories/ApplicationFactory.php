<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'event_id' => Event::inRandomOrder()->value('id') ?? Event::factory(),
            'status' => fake()->randomElement(['pending','accepted','rejected']),
        ];
    }
}
