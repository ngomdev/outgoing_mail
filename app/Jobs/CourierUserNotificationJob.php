<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\SendNotificationToCourierUser;

class CourierUserNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $courier;

    /**
     * Create a new job instance.
     */
    public function __construct($courier)
    {
        $this->courier = $courier;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $courierUsers = $this->courier->coursers()->whereHas('courser', function ($query) {
            $query->where('is_active', true);
        })
            ->where('notified', false)
            ->get();

        foreach ($courierUsers as $courierUser) {
            $courser = $courierUser->courser;
            $recipient = $courierUser->recipient;

            if ($courser->email) {
                $courser->notify(new SendNotificationToCourierUser($this->courier->courier_number, $courser, $recipient));
            }
        }

        $courierUsers->each(fn ($item) => $item->update([
            'assignment_date' => now(),
            'notified' => true
        ]));
    }
}
