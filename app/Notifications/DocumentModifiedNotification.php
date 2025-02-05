<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentModifiedNotification extends Notification
{
    use Queueable;

    private $userName;
    private $document;
    private $isFirstValidator;

    /**
     * Create a new notification instance.
     */
    public function __construct($userName, $document, $isFirstValidator)
    {
        $this->userName = $userName;
        $this->document = $document;
        $this->isFirstValidator = $isFirstValidator;
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
            ->subject("Orbus Courrier: Modification de document {$this->document->name}({$this->document->object})")
            ->greeting('Bonjour ' . $this->userName . ',')
            ->line('Des changements viennent d\'etre apportées au document ' . $this->document->name . '.')
            ->lineIf($this->isFirstValidator === true , 'Cliquez sur le bouton ci-dessous afin d\'accéder au document et procéder à sa validation.')
            ->lineIf($this->isFirstValidator === false , 'Cliquez sur le bouton ci-dessous pour accéder au document.')
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
