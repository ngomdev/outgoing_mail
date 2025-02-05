<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $code;
    private $expiration;

    /**
     * Create a new notification instance.
     */
    public function __construct($code, $expiration)
    {
        $this->code = $code;
        $this->expiration = $expiration;
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
        // $url = config('app.CLIENT_APP_URL') . '/reset-password/new-password/' . $this->code;
        $remainingTime = ceil(now()->diffInMinutes($this->expiration));
        return (new MailMessage)
            ->subject('Orbus Courrier: Réinitialisation mot de passe')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Vous avez demandé la réinitialisation de votre mot de passe. Pour procéder à cette opération, veuillez utiliser le code suivant :')
            ->line(new HtmlString("Code de réinitialisation : <b>$this->code</b>"))
            ->line(new HtmlString("Ce code expirera dans <b>$remainingTime minutes</b>. Veuillez ne pas partager ce code avec d'autres personnes."))
            ->line('Si vous n\'avez pas demandé cette réinitialisation, veuillez ignorer cet email.');
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
