<?php

namespace App\Models;

use App\Models\User;
use App\Models\Courier;
use App\Models\Recipient;
use App\Enums\CourierStatus;
use App\Enums\RecipientType;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CourierUser extends Pivot
{
    use HasFactory;

    protected $casts = [
        'type' => RecipientType::class,
        'assignment_date' => 'datetime',
        'pickup_date' => 'datetime',
        'deposit_date' => 'datetime',
        'status' => CourierStatus::class,
        'notified' => 'boolean'
    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function courser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
