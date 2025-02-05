<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $url = Filament::getResetPasswordUrl($this->token, $notifiable);

        return (new MailMessage)
            ->subject(Lang::get('Orbus Courier: Création de compte utilisateur'))
            ->line(Lang::get('Vous recevez cet email car un compte a été créé pour vous.'))
            ->action(Lang::get('Définir un mot de passe'), $url)
            ->line(Lang::get('Ce lien expirera dans :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('Si vous n\'avez pas demandé la création de ce compte, veuillez ignorer cet email.'));
    }
}
