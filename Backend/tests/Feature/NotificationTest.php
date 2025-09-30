<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Application;
use App\Models\Organization;
use App\Models\EventReview;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that notification is sent when user applies to event
     */
    public function test_notification_sent_when_applying_to_event()
    {
        Notification::fake();

        $volunteer = User::factory()->create();
        $orgOwner = User::factory()->create();
        $organization = Organization::factory()->create(['user_id' => $orgOwner->id]);
        $event = Event::factory()->create(['organization_id' => $organization->id]);

        // Apply to event
        $this->actingAs($volunteer, 'sanctum')
            ->postJson("/api/events/{$event->id}/apply")
            ->assertStatus(201);

        // Check notifications were sent
        Notification::assertSentTo(
            $volunteer,
            \App\Notifications\ApplicationSubmitted::class
        );

        Notification::assertSentTo(
            $orgOwner,
            \App\Notifications\NewApplicationReceived::class
        );
    }

    /**
     * Test that notification is sent when application status changes
     */
    public function test_notification_sent_when_application_status_changes()
    {
        Notification::fake();

        $volunteer = User::factory()->create();
        $orgOwner = User::factory()->create();
        $organization = Organization::factory()->create(['user_id' => $orgOwner->id]);
        $event = Event::factory()->create(['organization_id' => $organization->id]);
        $application = Application::factory()->create([
            'user_id' => $volunteer->id,
            'event_id' => $event->id,
            'status' => 'pending'
        ]);

        // Accept application
        $this->actingAs($orgOwner, 'sanctum')
            ->patchJson("/api/applications/{$application->id}/status", [
                'status' => 'accepted'
            ])
            ->assertStatus(200);

        // Check notifications were sent to both volunteer and organization
        Notification::assertSentTo(
            $volunteer,
            \App\Notifications\ApplicationStatusChanged::class
        );

        Notification::assertSentTo(
            $orgOwner,
            \App\Notifications\ApplicationStatusChanged::class
        );
    }

    /**
     * Test that notification is sent when review is added
     */
    public function test_notification_sent_when_review_added()
    {
        Notification::fake();

        $volunteer = User::factory()->create();
        $orgOwner = User::factory()->create();
        $organization = Organization::factory()->create(['user_id' => $orgOwner->id]);
        $event = Event::factory()->create(['organization_id' => $organization->id]);

        // Add review
        $this->actingAs($volunteer, 'sanctum')
            ->postJson("/api/events/{$event->id}/reviews", [
                'rating' => 5,
                'comment' => 'Great event!'
            ])
            ->assertStatus(201);

        // Check notification was sent to organization owner
        Notification::assertSentTo(
            $orgOwner,
            \App\Notifications\NewReviewReceived::class
        );
    }

    /**
     * Test that notification is sent when event is created
     */
    public function test_notification_sent_when_event_created()
    {
        Notification::fake();

        $orgOwner = User::factory()->create();
        $organization = Organization::factory()->create(['user_id' => $orgOwner->id]);

        // Create event
        $this->actingAs($orgOwner, 'sanctum')
            ->postJson('/api/events', [
                'name' => 'Test Event',
                'description' => 'Test Description',
                'organization_id' => $organization->id,
                'start_time' => now()->addDays(7)->toDateTimeString(),
                'end_time' => now()->addDays(7)->addHours(3)->toDateTimeString(),
                'location' => 'Test Location',
                'max_volunteers' => 10
            ])
            ->assertStatus(201);

        // Check notification was sent to organization owner
        Notification::assertSentTo(
            $orgOwner,
            \App\Notifications\EventCreated::class
        );
    }

    /**
     * Test that notifications are sent when event is updated
     */
    public function test_notifications_sent_when_event_updated()
    {
        Notification::fake();

        $orgOwner = User::factory()->create();
        $volunteer1 = User::factory()->create();
        $volunteer2 = User::factory()->create();
        $organization = Organization::factory()->create(['user_id' => $orgOwner->id]);
        $event = Event::factory()->create(['organization_id' => $organization->id]);
        
        // Create applications
        Application::factory()->create([
            'user_id' => $volunteer1->id,
            'event_id' => $event->id
        ]);
        Application::factory()->create([
            'user_id' => $volunteer2->id,
            'event_id' => $event->id
        ]);

        // Update event
        $this->actingAs($orgOwner, 'sanctum')
            ->putJson("/api/events/{$event->id}", [
                'name' => 'Updated Event Name',
                'description' => $event->description,
                'organization_id' => $organization->id,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'location' => $event->location,
                'max_volunteers' => $event->max_volunteers
            ])
            ->assertStatus(200);

        // Check notifications were sent to owner and all applicants
        Notification::assertSentTo(
            $orgOwner,
            \App\Notifications\EventUpdated::class
        );

        Notification::assertSentTo(
            $volunteer1,
            \App\Notifications\EventUpdated::class
        );

        Notification::assertSentTo(
            $volunteer2,
            \App\Notifications\EventUpdated::class
        );
    }
}
