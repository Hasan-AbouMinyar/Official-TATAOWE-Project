<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Application;
use App\Models\Event;
use App\Models\EventReview;
use App\Notifications\ApplicationStatusChanged;
use App\Notifications\NewApplicationReceived;
use App\Notifications\ApplicationSubmitted;
use App\Notifications\NewReviewReceived;
use App\Notifications\EventCreated;
use App\Notifications\EventUpdated;
use Illuminate\Database\Seeder;

class ComprehensiveNotificationSeeder extends Seeder
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

        // 1. Application Status Changed Notifications
        $applications = Application::with(['event', 'user'])
            ->where('user_id', $user->id)
            ->take(2)
            ->get();

        foreach ($applications as $application) {
            $user->notify(new ApplicationStatusChanged($application, 'accepted'));
            $this->command->info('✓ Created ApplicationStatusChanged notification');
            sleep(1);
        }

        // 2. New Application Received (for organization owners)
        $organizationApplications = Application::with(['event.organization', 'user'])
            ->whereHas('event.organization', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->take(2)
            ->get();

        foreach ($organizationApplications as $application) {
            $user->notify(new NewApplicationReceived($application));
            $this->command->info('✓ Created NewApplicationReceived notification');
            sleep(1);
        }

        // 3. Application Submitted Confirmation
        if ($applications->isNotEmpty()) {
            $user->notify(new ApplicationSubmitted($applications->first()));
            $this->command->info('✓ Created ApplicationSubmitted notification');
            sleep(1);
        }

        // 4. New Review Received
        $reviews = EventReview::with(['event.organization', 'user'])
            ->whereHas('event.organization', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->take(2)
            ->get();

        foreach ($reviews as $review) {
            $user->notify(new NewReviewReceived($review));
            $this->command->info('✓ Created NewReviewReceived notification');
            sleep(1);
        }

        // 5. Event Created
        $userEvents = Event::with('organization')
            ->whereHas('organization', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->take(1)
            ->get();

        foreach ($userEvents as $event) {
            $user->notify(new EventCreated($event));
            $this->command->info('✓ Created EventCreated notification');
            sleep(1);
        }

        // 6. Event Updated
        foreach ($userEvents as $event) {
            $user->notify(new EventUpdated($event));
            $this->command->info('✓ Created EventUpdated notification');
            sleep(1);
        }

        $this->command->info('🎉 All comprehensive notifications created successfully!');
    }
}
