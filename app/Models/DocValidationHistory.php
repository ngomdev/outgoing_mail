<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocValidationHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'user_id',
        'validation_date',
        'is_active'
    ];

    protected $casts = [
        'validation_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
