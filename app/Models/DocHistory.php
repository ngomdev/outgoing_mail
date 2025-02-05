<?php

namespace App\Models;

use App\Enums\DocAction;
use App\Models\User;
use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_id',
        'version',
        'action',
        'doc_path'
    ];

    protected $casts = [
        'action' => DocAction::class
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
