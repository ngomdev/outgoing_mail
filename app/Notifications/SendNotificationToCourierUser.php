<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendNotificationToCourierUser extends Notification implements ShouldQueue
{
    use Queueable;

    private $courierNumber;

    private $courser;

    private $recipient;

    /**
     * Create a new notification instance.
     */
    public function __construct($courierNumber, $courser, $recipient)
    {
        $this->courierNumber = $courierNumber;
        $this->courser = $courser;
        $this->recipient = $recipient;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'vonage'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Orbus Courrier: Assignation Courrier')
            ->greeting('Bonjour ' . $this->courser->name . ',')
            ->line('Vous venez d\'être assigné au courrier ' . $this->courierNumber . ' adressé à ' . $this->recipient->name . '.')
            ->line('Veuillez vous rendre sur place pour récupèrer le courrier.');
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
