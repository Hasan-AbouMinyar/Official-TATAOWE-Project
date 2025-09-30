<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusChanged extends Notification
{
    use Queueable;

    protected $application;
    protected $newStatus;
    protected $isForOrganization;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application, string $newStatus, bool $isForOrganization = false)
    {
        $this->application = $application;
        $this->newStatus = $newStatus;
        $this->isForOrganization = $isForOrganization;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $this->application->load(['user', 'event']);
        
        if ($this->isForOrganization) {
            // Notification for organization owner
            $statusText = match($this->newStatus) {
                'accepted' => 'accepted',
                'rejected' => 'rejected',
                default => 'updated'
            };
            
            return [
                'type' => 'application_status',
                'title' => 'Application Status Updated',
                'message' => "You {$statusText} {$this->application->user->name}'s application for \"{$this->application->event->name}\"",
                'application_id' => $this->application->id,
                'event_id' => $this->application->event->id,
                'user_id' => $this->application->user->id,
                'user_name' => $this->application->user->name,
                'event_name' => $this->application->event->name,
                'status' => $this->newStatus,
                'icon' => match($this->newStatus) {
                    'accepted' => 'check-circle',
                    'rejected' => 'x-circle',
                    default => 'clock'
                },
                'color' => match($this->newStatus) {
                    'accepted' => 'green',
                    'rejected' => 'red',
                    default => 'yellow'
                }
            ];
        } else {
            // Notification for applicant (volunteer)
            $statusMessages = [
                'pending' => 'Your application is under review',
                'accepted' => 'Congratulations! Your application has been accepted',
                'rejected' => 'Unfortunately, your application was not accepted this time',
            ];

            return [
                'type' => 'application_status',
                'title' => 'Application Status Update',
                'message' => $statusMessages[$this->newStatus] ?? 'Your application status has been updated',
                'status' => $this->newStatus,
                'application_id' => $this->application->id,
                'event_id' => $this->application->event_id,
                'event_name' => $this->application->event->name ?? null,
                'icon' => $this->newStatus === 'accepted' ? 'check-circle' : ($this->newStatus === 'rejected' ? 'x-circle' : 'clock'),
                'color' => $this->newStatus === 'accepted' ? 'green' : ($this->newStatus === 'rejected' ? 'red' : 'yellow'),
            ];
        }
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $this->application->load(['user', 'event']);
        
        if ($this->isForOrganization) {
            // Email for organization owner
            $statusText = match($this->newStatus) {
                'accepted' => 'accepted',
                'rejected' => 'rejected',
                default => 'updated'
            };
            
            $color = match($this->newStatus) {
                'accepted' => 'success',
                'rejected' => 'error',
                default => 'info'
            };
            
            return (new MailMessage)
                ->subject('Application Status Updated - ' . $this->application->event->name)
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line("You {$statusText} {$this->application->user->name}'s application for \"{$this->application->event->name}\"")
                ->action('View Details', url('/events/' . $this->application->event->id))
                ->line('Thank you for using Tatawoe platform!')
                ->{$color}();
        } else {
            // Email for applicant (volunteer)
            $statusMessages = [
                'pending' => 'Your application is under review',
                'accepted' => 'Congratulations! Your application has been accepted',
                'rejected' => 'Unfortunately, your application was not accepted this time',
            ];
            
            $color = match($this->newStatus) {
                'accepted' => 'success',
                'rejected' => 'error',
                default => 'info'
            };

            return (new MailMessage)
                ->subject('Application Status Update - ' . $this->application->event->name)
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line($statusMessages[$this->newStatus] ?? 'Your application status has been updated')
                ->line('Event: ' . $this->application->event->name)
                ->action('View Details', url('/events/' . $this->application->event->id))
                ->line('Thank you for using Tatawoe platform!')
                ->{$color}();
        }
    }
}
