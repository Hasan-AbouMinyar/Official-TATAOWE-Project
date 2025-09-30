<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Application;
use App\Notifications\ApplicationStatusChanged;
use App\Notifications\NewApplicationReceived;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the test user
        $user = User::where('email', 'abouminyar@gmail.com')->first();
        
        if (!$user) {
            $this->command->warn('User not found!');
            return;
        }

        // Get some applications for testing
        $applications = Application::with(['event', 'user'])
            ->where('user_id', $user->id)
            ->take(3)
            ->get();

        if ($applications->isEmpty()) {
            $this->command->warn('No applications found for user!');
            return;
        }

        // Create test notifications
        foreach ($applications as $application) {
            // Send accepted notification
            $user->notify(new ApplicationStatusChanged($application, 'accepted'));
            $this->command->info('Created acceptance notification for application ' . $application->id);
            
            sleep(1); // Small delay to create different timestamps
        }

        $this->command->info('Test notifications created successfully!');
    }
}
