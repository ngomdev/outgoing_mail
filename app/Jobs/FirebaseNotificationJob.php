<?php

namespace App\Jobs;

use App\Models\CourierUser;
use App\Models\DocumentUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\FirebaseNotificationService;

class FirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $targetUsers;

    private $actionType;

    private $targetItem;


    /**
     * Create a new job instance.
     */
    public function __construct($targetUsers, $actionType, $targetItem = null)
    {
        $this->targetUsers = $targetUsers;
        $this->actionType = $actionType;
        $this->targetItem = $targetItem;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $targetType = "";
        $recipientName = "";
        $courierNo = "";
        $notifyBySms = false;

        foreach ($this->targetUsers as $targetUser) {

            if ($targetUser instanceof DocumentUser) {
                $targetType = 'document';
                $notifiable =  $targetUser->user;
            } else {
                $targetType = 'courier';
                $notifiable =  $targetUser?->courser ?? $targetUser;
                $recipientName = $this->targetItem ? $this->targetItem?->recipient?->name : $targetUser->recipient->name;
                $courierNo = $this->targetItem ? $this->targetItem->courier->courier_number : $targetUser->courier->courier_number;
                // Check if user should be notified by sms
                $notifyBySms = $targetUser instanceof CourierUser && isset($notifiable->phone) ? true : $notifyBySms;
            }

            if ($notifiable->is_active) {

                switch ($this->actionType) {
                    case "validation": {
                            $notificationTitle = "Attente validation document";
                            $notificationBody = "C'est à votre tour de valider le document " . $targetUser->document->name;
                            break;
                        }

                    case "signature": {
                            $notificationTitle = "Attente signature document";
                            $notificationBody = "Le document" . $targetUser->document->name . " est prêt pour signature.";
                            break;
                        }

                    case "assigné": {
                            $notificationTitle = "Assignation courrier";
                            $notificationBody = "Le courrier n°" . $courierNo . " adressé à " . $recipientName . " vient de vous être assigné.";
                            break;
                        }

                    case "distribué": {
                            $notificationTitle = "Distribution courrier";
                            $notificationBody = "Le courrier n°" . $courierNo . " adressé à " . $recipientName . " vient d'être distribué.";
                            break;
                        }
                    case "non_distribué": {
                            $notificationTitle = "Distribution courrier";
                            $notificationBody = "Le courrier n°" . $courierNo . " adressé à " . $recipientName . " n'a pas été distribué.";
                            break;
                        }

                    case "rejeté": {
                            $notificationTitle = "Distribution courrier";
                            $notificationBody = "Le courrier n°" . $courierNo . " adressé à " . $recipientName . " vient d'être rejeté.";
                            break;
                        }

                    default: {
                            $notificationTitle = "Notification";
                            $notificationBody = "notification par défaut";
                            break;
                        }
                }


                $notifiable->firebaseNotifications()->updateOrCreate([
                    'target_id' => $this->targetItem ? $this->targetItem->id : $targetUser->id,
                    'target_type' => $targetType,
                    'action' => $this->actionType,
                    'title' => $notificationTitle,
                    'body' => $notificationBody
                ]);

                (new FirebaseNotificationService())->sendNotification($notificationTitle, $notificationBody, $notifiable->fcm_token);

                // Send sms to user
                if ($notifyBySms) {
                    (new FirebaseNotificationService())->sendSms($notifiable->phone, $notificationBody);
                }
            }
        }
    }
}
