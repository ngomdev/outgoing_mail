<?php

namespace App\Models;

use App\Enums\SignatureType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => SignatureType::class
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
