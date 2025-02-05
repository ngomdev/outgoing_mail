<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CourierRecipient extends Pivot
{
    use HasFactory;

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }
}
