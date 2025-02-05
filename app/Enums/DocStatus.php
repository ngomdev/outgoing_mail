<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;


enum DocStatus: string implements HasLabel, HasColor, HasIcon
{
    case DRAFT = 'draft';
    case INITIATED = 'initialized';
    case VALIDATING = 'validating';
    case MODIFIED = 'modified';
    case VALIDATED = 'validated';
    case SIGNED = 'signed';
    case ARCHIVED = 'archived';
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::INITIATED => 'Initialisé',
            self::VALIDATING => 'En cours de validation',
            self::MODIFIED => 'Modifié',
            self::VALIDATED => 'Validé',
            self::SIGNED => 'Signé',
            self::ARCHIVED => 'Archivé',
            self::CANCELLED => 'Annulé'
        };
    }


    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::INITIATED => 'secondary',
            self::VALIDATING => 'primary',
            self::MODIFIED => 'warning',
            self::VALIDATED => 'success',
            self::SIGNED => 'success',
            self::ARCHIVED => 'info',
            self::CANCELLED => 'danger',
        };
    }


    public function getRgbColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'rgb(192,192,192)',
            self::INITIATED => 'rgb(102,178,255)',
            self::VALIDATING => 'rgb(255,178,102)',
            self::VALIDATED => 'rgb(102,255,178)',
            self::SIGNED => 'rgb(51,255,153)',
            self::ARCHIVED => 'rgb(204,153,255)',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-m-pencil',
            self::INITIATED => 'heroicon-m-check',
            self::VALIDATING => 'heroicon-m-check',
            self::MODIFIED => 'heroicon-m-pencil',
            self::VALIDATED => 'heroicon-m-check',
            self::SIGNED => 'heroicon-m-check',
            self::ARCHIVED => 'heroicon-m-archive-box-arrow-down',
            self::CANCELLED => 'heroicon-m-x-circle',
        };
    }

    public function getRank(): ?int
    {
        return match ($this) {
            self::DRAFT => 1,
            self::INITIATED => 2,
            self::VALIDATING => 3,
            self::MODIFIED => 4,
            self::VALIDATED => 5,
            self::SIGNED => 6,
            self::ARCHIVED => 7,
            self::CANCELLED => 8,
        };
    }

    public static function labels(): array
    {
        return [
            self::DRAFT => 'Draft',
            self::INITIATED => 'Initialisé',
            self::VALIDATING => 'En cours de validation',
            self::MODIFIED => 'Modifié',
            self::VALIDATED => 'Validé',
            self::SIGNED => 'Signé',
            self::ARCHIVED => 'Archivé',
        ];
    }
}
