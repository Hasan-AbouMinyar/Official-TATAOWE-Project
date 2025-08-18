<?php

namespace Database\Factories;

use App\Models\Skills;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skills>
 */
class SkillsFactory extends Factory
{
    protected $model = Skills::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'skill_name' => fake()->randomElement([
                'Communication','Teamwork','Leadership','Problem Solving','Time Management','Adaptability','Creativity','Project Management','Public Speaking','Data Analysis','Web Development','Graphic Design'
            ]),
        ];
    }
}
