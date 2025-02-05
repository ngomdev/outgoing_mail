<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum DocAction: string implements HasLabel, HasColor, HasIcon

{
    case CREATE = 'create';
    case EDIT = 'edit';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CREATE => 'Création',
            self::EDIT => 'Modification'
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::CREATE => 'primary',
            self::EDIT => 'info'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::CREATE => 'heroicon-m-plus-circle',
            self::EDIT => 'heroicon-m-pencil',
        };
    }
}
