<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendDocActionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $userName;
    private $document;

    /**
     * Create a new notification instance.
     */
    public function __construct($userName, $document)
    {
        $this->userName = $userName;
        $this->document = $document;
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
        return (new MailMessage)
            ->subject("Orbus Courrier: Attente Validation Document document {$this->document->name}({$this->document->object})")
            ->greeting('Bonjour ' . $this->userName . ',')
            ->line('C\'est a votre tour de valider le document ' . $this->document->name . '.')
            ->line('Cliquez sur le bouton ci-dessous afin d\'accéder au document et procéder à sa validation.')
            ->action('Voir document', url('/documents/' . $this->document->id));
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
