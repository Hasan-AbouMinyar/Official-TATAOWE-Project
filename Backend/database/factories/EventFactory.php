<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+'.fake()->numberBetween(1, 8).' hours');
        return [
            'organization_id' => Organization::inRandomOrder()->value('id') ?? Organization::factory(),
            'name' => fake()->catchPhrase(),
            'description' => fake()->paragraph(),
            'start_time' => $start,
            'end_time' => $end,
            'location' => fake()->city(),
            'requiredSkills' => implode(',', fake()->randomElements([
                'communication','teamwork','leadership','organization','planning','design','marketing','fundraising','teaching','coding'
            ], fake()->numberBetween(1,4))),
            'photo' => null,
        ];
    }
}
