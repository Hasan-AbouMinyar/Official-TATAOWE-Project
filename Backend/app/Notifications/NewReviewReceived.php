<?php

namespace App\Notifications;

use App\Models\EventReview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewReviewReceived extends Notification
{
    use Queueable;

    protected $review;

    public function __construct(EventReview $review)
    {
        $this->review = $review;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $this->review->load(['user', 'event']);
        
        $stars = str_repeat('⭐', $this->review->rating);
        
        return (new MailMessage)
            ->subject('New Review on Your Event - ' . $this->review->event->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->review->user->name . ' left a review on your event')
            ->line('Event: ' . $this->review->event->name)
            ->line('Rating: ' . $stars . ' (' . $this->review->rating . '/5)')
            ->when($this->review->comment, function($mail) {
                return $mail->line('Comment: ' . $this->review->comment);
            })
            ->action('View All Reviews', url('/events/' . $this->review->event->id))
            ->line('Thank you for using Tatawoe platform!')
            ->success();
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_review',
            'title' => 'New Review on Your Event',
            'message' => $this->review->user->name . ' left a ' . $this->review->rating . '-star review on your event',
            'review_id' => $this->review->id,
            'event_id' => $this->review->event_id,
            'event_name' => $this->review->event->name ?? null,
            'user_name' => $this->review->user->name ?? null,
            'rating' => $this->review->rating,
            'comment' => $this->review->comment,
            'icon' => 'star',
            'color' => 'yellow',
        ];
    }
}
