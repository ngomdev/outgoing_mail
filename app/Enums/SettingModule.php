<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SettingModule: string implements HasLabel
{
    case SYSTEM = 'system';
    case DOCUMENT = 'document';
    case COURIER = 'courier';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::SYSTEM => 'system',
            self::DOCUMENT => 'document',
            self::COURIER => 'courier'
        };
    }
}
