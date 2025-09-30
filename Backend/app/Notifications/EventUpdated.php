<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventUpdated extends Notification
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
            ->subject('Event Updated - ' . $this->event->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The event has been updated: ' . $this->event->name)
            ->line('Please review the new event details.')
            ->action('View Event', url('/events/' . $this->event->id))
            ->line('Thank you for using Tatawoe platform!')
            ->success();
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'event_updated',
            'title' => 'Event Updated',
            'message' => 'The event "' . $this->event->name . '" has been updated',
            'event_id' => $this->event->id,
            'event_name' => $this->event->name,
            'icon' => 'bell',
            'color' => 'blue',
        ];
    }
}
