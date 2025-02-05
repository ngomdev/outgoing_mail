<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendDocInformativeNotification extends Notification
{
    use Queueable;

    private $userName;
    private $document;
    private $isCurrentValidator;
    private $validatorAdded;
    private $roleCourier;

    /**
     * Create a new notification instance.
     */
    public function __construct($validatorAdded = null, $userName, $document, $isCurrentValidator = null, $roleCourier = null)
    {
        $this->validatorAdded = $validatorAdded;
        $this->userName = $userName;
        $this->document = $document;
        $this->isCurrentValidator = $isCurrentValidator;
        $this->roleCourier = $roleCourier;
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

        // TODO Body Mail Ajout Signataire
        if ($this->roleCourier) {
            return (new MailMessage)
                ->subject("Orbus Courrier: Ajout de Signataire à document {$this->document->name}({$this->document->object})")
                ->greeting('Bonjour ' . $this->userName . ',')
                ->line('Vous venez d\'être ajouté au document ' . $this->document->name . ' en tant que ' . $this->roleCourier . '.')
                ->line('Cliquez sur le bouton ci-dessous pour accéder au document.')
                ->action('Voir document', url('/documents/' . $this->document->id));
        }

         // TODO Body Mail Document Modifie
        if (!$this->validatorAdded) {
            return (new MailMessage)
                ->subject("Orbus Courrier: Modification de document {$this->document->name}({$this->document->object})")
                ->greeting('Bonjour ' . $this->userName . ',')
                ->line('Des changements viennent d\'etre apportées au document ' . $this->document->name . '.')
                ->lineIf($this->isCurrentValidator === true, 'Cliquez sur le bouton ci-dessous afin d\'accéder au document et procéder à sa validation.')
                ->lineIf($this->isCurrentValidator === false, 'Cliquez sur le bouton ci-dessous pour accéder au document.')
                ->action('Voir document', url('/documents/' . $this->document->id));
        }


         // TODO Body Mail Ajout Parapheur
        return (new MailMessage)
            ->subject("Orbus Courrier: Ajout de validateur à document {$this->document->name}({$this->document->object})")
            ->greeting('Bonjour ' . $this->userName . ',')
            ->line('Vous venez d\'être ajouté au document ' . $this->document->name . ' en tant que Parapheur.')
            ->lineIf($this->isCurrentValidator === true, 'Cliquez sur le bouton ci-dessous pour accéder au document et procéder à sa validation.')
            ->lineIf($this->isCurrentValidator === false, 'Cliquez sur le bouton ci-dessous pour accéder au document.')
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
