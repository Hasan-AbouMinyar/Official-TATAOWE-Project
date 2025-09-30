<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ApplicationSubmitted extends Notification
{
    use Queueable;

    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->application->load('event');
        
        return (new MailMessage)
            ->subject('Application Submitted Successfully - ' . $this->application->event->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your application for the event: ' . $this->application->event->name . ' has been submitted')
            ->line('Application Status: Under Review')
            ->action('View My Applications', url('/applications'))
            ->line('Your application will be reviewed soon.')
            ->line('Thank you for using Tatawoe platform!')
            ->success();
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_submitted',
            'title' => 'Application Submitted',
            'message' => 'Your application for "' . ($this->application->event->name ?? 'the event') . '" has been submitted successfully',
            'application_id' => $this->application->id,
            'event_id' => $this->application->event_id,
            'event_name' => $this->application->event->name ?? null,
            'status' => $this->application->status,
            'icon' => 'mail',
            'color' => 'blue',
        ];
    }
}
