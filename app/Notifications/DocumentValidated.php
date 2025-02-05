<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentValidated extends Notification implements ShouldQueue
{
    use Queueable;

    public $document;

    /**
     * Create a new notification instance.
     */
    public function __construct($document)
    {
        $this->afterCommit();

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
        $url = url("/documents/{$this->document->id}");

        return (new MailMessage)
            ->subject("Document validé!")
            ->greeting("Hello $notifiable->name!")
            ->line("Le document {$this->document->name}({$this->document->object}) a passé le processus de validation")
            ->lineIf(!$this->document->signataires, "Vous n'avez pas encore désigné de signataire,")
            ->lineIf(!$this->document->signataires, "Rendez-vous dans l'application pour en désigner un et poursuivre le processus!")
            ->line("Cliquez sur le bouton *Voir document* ci-dessous pour y accéder rapidement!")
            ->action('Voir document', $url)
            ->line('Cordialement');
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
