<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationReceived extends Notification
{
    use Queueable;

    protected $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $this->application->load(['user', 'event']);
        
        return (new MailMessage)
            ->subject('New Application for Your Event - ' . $this->application->event->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('You have a new application for the event: ' . $this->application->event->name)
            ->line('Applicant: ' . $this->application->user->name)
            ->action('View Application', url('/events/' . $this->application->event->id . '/applications'))
            ->line('Thank you for using Tatawoe platform!')
            ->success();
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_application',
            'title' => 'New Application Received',
            'message' => 'You have a new application for your event',
            'application_id' => $this->application->id,
            'event_id' => $this->application->event_id,
            'event_name' => $this->application->event->name ?? null,
            'user_name' => $this->application->user->name ?? 'Someone',
            'icon' => 'mail',
            'color' => 'blue',
        ];
    }
}
