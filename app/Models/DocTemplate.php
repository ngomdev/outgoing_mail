<?php

namespace App\Models;

use App\Enums\DocType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocTemplate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'doc_type',
        'name',
        'content',
        'file_path'
    ];

    protected $casts = [
        'doc_type' => DocType::class
    ];
}
