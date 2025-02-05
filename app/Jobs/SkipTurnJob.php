<?php

namespace App\Jobs;

use App\Enums\DocStatus;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\ViewDocument;
use App\Models\Document;
use Carbon\CarbonImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SkipTurnJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $documents = Document::whereIn('status', [DocStatus::INITIATED->value, DocStatus::VALIDATING->value])->get();

        $documents->each(function ($document) {
            $urgency = $document->doc_urgency;
            $currentValidator = $document->currentValidator;
            $nextValidator = $document->nextValidator;
            $requestDate = CarbonImmutable::parse($currentValidator->action_request_date);
            $delayExpirationDate = $requestDate->addHours($urgency->getValue());

            if ($nextValidator && $delayExpirationDate <= now()->toDateTimeImmutable()) {
                $currentValidator->moveToEnd();

                $nextValidator->update([
                    'action_request_date' => now()
                ]);

                // TODO Send Notification to next validator
                (new ViewDocument())->notifyUser($nextValidator->user, $document);

                // Send firebase notification to user for validation action
                FirebaseNotificationJob::dispatch([$nextValidator], "validation");
            }
        });
    }
}
