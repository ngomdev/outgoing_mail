<?php

namespace App\Models;

use App\Enums\RoleEnum;
use App\Models\Team;
use App\Models\User;
use App\Enums\DocType;
use App\Models\Courier;
use App\Enums\DocStatus;
use App\Enums\DocUrgency;
use App\Models\DocHistory;
use App\Models\DocumentTeam;
use Spatie\Activitylog\LogOptions;
use App\Models\ExternalDocInitiator;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Parallax\FilamentComments\Models\Traits\HasFilamentComments;


class Document extends Model
{
    use HasFactory, LogsActivity, HasFilamentComments;

    protected $fillable = [
        'recipient_id',
        'name',
        'object',
        'doc_type',
        'doc_urgency',
        'status',
        'doc_content',
        'doc_path',
        'doc_created_at',
        'initiator',
        'created_by',
        'external_doc_initiator_id',
        'should_be_expedited'
    ];

    protected $casts = [
        'status' => DocStatus::class,
        'doc_urgency' => DocUrgency::class,
        'doc_type' => DocType::class,
        'doc_created_at' => 'datetime',
        'attachments' => 'array',
        'should_be_expedited' => 'boolean'
    ];

    public function recipient()
    {
        return $this->belongsTo(Recipient::class);
    }

    public function couriers()
    {
        return $this->hasMany(Courier::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documentUsers()
    {
        return $this->hasMany(DocumentUser::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function documentTeams()
    {
        return $this->hasMany(DocumentTeam::class);
    }

    public function parapheurs()
    {
        return $this->hasMany(DocumentUser::class)
            ->whereRelation('role', 'name', RoleEnum::PARAPHEUR->getLabel())
            ->ordered();
    }

    public function signataires()
    {
        return $this->hasMany(DocumentUser::class)
            ->whereHas('role', fn ($q) => $q->whereIn('name', [
                RoleEnum::SIGN_MAIN->getLabel(),
                RoleEnum::SIGN_ORDER->getLabel(),
                RoleEnum::SIGN_INTERIM->getLabel(),
                RoleEnum::SIGN_DELEGATION->getLabel()
            ]));
    }

    public function docHistory()
    {
        return $this->hasMany(DocHistory::class);
    }

    public function getCurrentValidatorAttribute()
    {
        $parapheurs = $this->parapheurs;


        foreach ($parapheurs as $parapheur) {
            $lastValidation = $parapheur->user->lastDocValidationHistory($this);
            if (!$lastValidation) {
                return $parapheur;
            } else {
                if (!$lastValidation->is_active) {
                    return $parapheur;
                }
            }
        }

        return null;
    }

    public function getNextValidatorAttribute()
    {
        return $this->currentValidator ? $this->parapheurs()
            ->where('order_column', '>', $this->currentValidator->order_column)
            ->first() : null;
    }

    protected function getLatestVersionAttribute()
    {
        return $this->docHistory()
            ->latest()
            ->first()
            ?->version;
    }

    public function getInitiatorUserAttribute()
    {
        return $this->initiator ?? $this->documentUsers()
            ->whereRelation('role', 'name', RoleEnum::INITIATOR->getLabel())?->first()?->user;
    }

    public function externalInitiator()
    {
        return $this->belongsTo(ExternalDocInitiator::class, 'external_doc_initiator_id');
    }

    public function validationHistory()
    {
        return $this->hasMany(DocValidationHistory::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'doc_path',
            ]);
    }
}
