<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Application;
use App\Notifications\ApplicationStatusChanged;
use App\Notifications\NewApplicationReceived;

class OrganizationNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find organization owner (user_id 24 who owns organizations)
        $orgOwner = User::find(24);
        
        if (!$orgOwner) {
            $this->command->error('Organization owner (user_id 24) not found!');
            return;
        }

        // Find some applications related to this organization's events
        $applications = Application::whereHas('event.organization', function($query) use ($orgOwner) {
            $query->where('user_id', $orgOwner->id);
        })->take(5)->get();

        if ($applications->isEmpty()) {
            $this->command->warn('No applications found for this organization');
            return;
        }

        $count = 0;

        foreach ($applications as $application) {
            // 1. New application received notification
            $orgOwner->notify(new NewApplicationReceived($application));
            $count++;
            $this->command->info("Created NewApplicationReceived notification for application #{$application->id}");

            // 2. Application accepted notification (to organization)
            $orgOwner->notify(new ApplicationStatusChanged($application, 'accepted', true));
            $count++;
            $this->command->info("Created ApplicationStatusChanged (accepted, org) for application #{$application->id}");

            // 3. Application rejected notification (to organization)
            $orgOwner->notify(new ApplicationStatusChanged($application, 'rejected', true));
            $count++;
            $this->command->info("Created ApplicationStatusChanged (rejected, org) for application #{$application->id}");
        }

        $this->command->info("✅ Successfully created {$count} organization notifications!");
    }
}
