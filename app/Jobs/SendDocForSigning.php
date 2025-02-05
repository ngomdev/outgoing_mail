<?php

namespace App\Jobs;

use Throwable;
use App\Models\Document;
use Illuminate\Bus\Queueable;
use App\Services\DocSigningService;
use Illuminate\Queue\SerializesModels;
use Filament\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendDocForSigning implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Document $document)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $signingCode = $this->document->signataires->first()->signing_code;

        if (!$signingCode) {
            return;
        }

        (new DocSigningService)->signDocument($this->document->code_pin, $this->document->file_path);
    }

    /**
     * Handle a job failure.
     */
    public function failed(?Throwable $exception): void
    {
        Notification::make()
            ->success()
            ->title(__('Success'))
            ->body("Echec signature du document : {$exception->getMessage()}")
            ->persistent()
            ->send();

        return;
    }
}
