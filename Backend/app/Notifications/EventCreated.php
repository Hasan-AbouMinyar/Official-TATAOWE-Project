<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventCreated extends Notification
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Event Created - ' . $this->event->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your event has been created successfully: ' . $this->event->name)
            ->line('Start Date: ' . $this->event->start_time->format('Y-m-d H:i'))
            ->action('View Event', url('/events/' . $this->event->id))
            ->line('Volunteers can now apply to your event.')
            ->line('Thank you for using Tatawoe platform!')
            ->success();
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'event_created',
            'title' => 'New Event Created',
            'message' => 'You successfully created the event "' . $this->event->name . '"',
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'event_date' => $this->event->start_time,
            'icon' => 'check-circle',
            'color' => 'green',
        ];
    }
}
