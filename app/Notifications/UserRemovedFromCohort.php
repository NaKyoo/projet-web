<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRemovedFromCohort extends Notification
{
    use Queueable;

    protected $cohort;

    /**
     * Create a new notification instance.
     */
    public function __construct($cohort)
    {
        $this->cohort = $cohort;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase(object $notifiable)
    {
        return [
            'message' => "Vous avez été retiré de la promotion « {$this->cohort->name} »",
            'start_date' => \Carbon\Carbon::parse($this->cohort->start_date)->format('d/m/Y'),
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
