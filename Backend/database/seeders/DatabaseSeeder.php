<?php

namespace Database\Seeders;

use App\Models\{User, Organization, Event, Application, Skills};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users
        $users = User::factory(20)->create();

        // Additional specific test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Seed skills for each user
        $users->each(function ($user) {
            Skills::factory(rand(2,5))->create(['user_id' => $user->id]);
        });
        Skills::factory(3)->create(['user_id' => $testUser->id]);

        // Organizations (each owned by random user)
        $organizations = Organization::factory(10)->create();

        // Events for organizations
        $events = $organizations->flatMap(function ($org) {
            return Event::factory(rand(1,4))->create(['organization_id' => $org->id]);
        });

        // Applications: users apply to random events
        $events->each(function ($event) use ($users) {
            $applicants = $users->random(rand(3,8));
            foreach ($applicants as $user) {
                Application::factory()->create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                ]);
            }
        });
    }
}
