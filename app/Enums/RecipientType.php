<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RecipientType: string implements HasLabel
{
    case MAIN = 'main';
    case ADDITIONAL = 'additional';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::MAIN => 'Principal',
            self::ADDITIONAL => 'Ampliataire'
        };
    }
}
