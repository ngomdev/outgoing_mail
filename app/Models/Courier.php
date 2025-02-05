<?php

namespace App\Models;

use App\Models\User;
use App\Models\Document;
use App\Models\CourierUser;
use App\Enums\CourierStatus;
use App\Enums\RecipientType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Courier extends Model
{
    use HasFactory;
    protected $fillable = [
        'document_id',
        'doc_path',
        'created_by',
        'courier_number',
        'object',
        'attachments',
        'status',
        'comment',
        'closure_date',
        'deposit_location_name',
        'courier_created_at'
    ];

    protected $casts = [
        'status' => CourierStatus::class,
        'attachments' => 'array'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function coursers()
    {
        return $this->hasMany(CourierUser::class, 'courier_id');
    }

    public function getMainRecipientAttribute()
    {
        return $this->coursers()
            ->where('type', RecipientType::MAIN)?->first()?->recipient;
    }

    public function getMainContactAttribute()
    {
        return $this->coursers()
            ->where('type', RecipientType::MAIN)?->first()?->contact;
    }

    public function getMainCourserAttribute()
    {
        return $this->coursers()
            ->where('type', RecipientType::MAIN)?->first()?->courser;
    }
}
