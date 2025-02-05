<?php

namespace App\Models;

use App\Enums\SettingModule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'display_name',
        'value',
        'description',
        'unit',
        'default_value',
        'is_active',

        'module'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'module' => SettingModule::class
    ];
}
