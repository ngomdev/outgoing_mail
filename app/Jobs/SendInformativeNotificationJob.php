<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Filament\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Filament\Notifications\Actions\Action;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\SendDocInformativeNotification;
use Filament\Notifications\Actions\Action as NotificationAction;

class SendInformativeNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $document;

    private $validatorAdded;

    /**
     * Create a new job instance.
     */
    public function __construct($validatorAdded, $document)
    {
        $this->validatorAdded = $validatorAdded;
        $this->document = $document;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // TODO Get Parapheurs
        $parapheursQuery = $this->document
            ->parapheurs()
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->with('user');

        // TODO Get Signataires
        $signatairesQuery = $this->document
            ->signataires()
            ->whereHas('user', fn ($query) => $query->where('is_active', true))
            ->with(['user', 'role']);

        if ($this->validatorAdded) {
            $parapheursQuery = $parapheursQuery->where('notified', false);
            $signatairesQuery = $signatairesQuery->where('notified', false);
        }

        $parapheurs = $parapheursQuery->get();

        $signataires = $signatairesQuery->get();

        // TODO Get the  current validator with the max order_column value
        $currentValidator = $this->document->currentValidator;

        // TODO Send Notification to each Parapheur
        foreach ($parapheurs as $parapheur) {
            try {
                $recipient = $parapheur->user;

                $isRecipientCurrentValidator = $recipient->id == $currentValidator->user->id;

                //TODO Construct Database Notification title
                $title = $this->validatorAdded ? "Ajout de validateur à document" : "Modification de document";
                $body = $isRecipientCurrentValidator ? "\nAttente de votre validation." : '';

                $recipient->notify(new SendDocInformativeNotification($this->validatorAdded, $recipient->name, $this->document, $isRecipientCurrentValidator));

                //TODO Update notified value for parapheur if validatorAdded value is true
                if ($this->validatorAdded) {
                    $parapheur->update(['notified' => true]);
                }

                // TODO Construct Database Notification body
                $body .= $this->validatorAdded
                    ? "Vous venez d'être ajouté au document " . $this->document->name . " en tant que Parapheur."
                    : "Des changements viennent d'être apportées au document " . $this->document->name . ".";

                // TODO Send Database Notification
                Notification::make()
                    ->title($title)
                    ->success()
                    ->body($body)
                    ->actions([
                        Action::make('Voir Document')
                            ->button()
                            ->url('/documents/' . $this->document->id),
                        Action::make('Marquer comme non lu')
                            ->button()
                            ->markAsUnread(),
                        Action::make('Marquer comme lu')
                            ->button()
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($recipient);
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Oups!')
                    ->body($e->getMessage())
                    ->actions([
                        NotificationAction::make('OK')
                            ->button()
                            ->close()
                    ])
                    ->persistent()
                    ->danger()
                    ->send();
            }
        }

        // TODO Send Notification to each Signataire
        foreach ($signataires as $signataire) {
            try {
                $recipient = $signataire->user;
                $roleCourier = $signataire->role->name;

                $recipient->notify(new SendDocInformativeNotification(null, $recipient->name, $this->document, null, $roleCourier));

                //TODO Update notified value for signataire
                $signataire->update(['notified' => true]);

                // TODO Send Database Notification
                Notification::make()
                    ->title("Ajout de Signataire à document")
                    ->success()
                    ->body("Vous venez d'être ajouté au document " . $this->document->name . " en tant que " . $roleCourier)
                    ->actions([
                        Action::make('Voir Document')
                            ->button()
                            ->url('/documents/' . $this->document->id),
                        Action::make('Marquer comme non lu')
                            ->button()
                            ->markAsUnread(),
                        Action::make('Marquer comme lu')
                            ->button()
                            ->markAsRead(),
                    ])
                    ->sendToDatabase($recipient);
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Oups!')
                    ->body($e->getMessage())
                    ->actions([
                        NotificationAction::make('OK')
                            ->button()
                            ->close()
                    ])
                    ->persistent()
                    ->danger()
                    ->send();
            }
        }
    }
}
