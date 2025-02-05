<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CourierDelivered extends Notification implements ShouldQueue
{
    use Queueable;

    public $courierUser;
    /**
     * Create a new notification instance.
     */
    public function __construct($courierUser)
    {
        $this->courierUser = $courierUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $courier = $this->courierUser->courier;
        $courser = $this->courierUser->courser;
        $recipient = $this->courierUser->recipient;

        $url = url("/courier/{$courier->id}");

        return(new MailMessage)->markdown('mail.courier.delivered', [
            "url" => $url,
            "courier" => $courier,
            "courser" => $courser,
            "recipient" => $recipient,
            "courierUser" => $this->courierUser,
            "notifiable" => $notifiable
        ]);
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
