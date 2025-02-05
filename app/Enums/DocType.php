<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DocType: string implements HasLabel
{
    case CONTRACT = 'contract';
    case TRANSCRIPT = 'transcript';
    case MEMORANDUM = 'memorandum';
    case LETTER = 'letter';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::CONTRACT => 'Contrat',
            self::TRANSCRIPT => 'PV',
            self::MEMORANDUM => 'Note de service',
            self::LETTER => 'Lettre'
        };
    }

    public static function getValues(): array
    {
        return [
            self::CONTRACT,
            self::TRANSCRIPT,
            self::MEMORANDUM,
            self::LETTER,
        ];
    }

    public static function search($label): ?string
    {
        return match ($label) {
            self::CONTRACT::getLabel => self::CONTRACT->value,
            self::TRANSCRIPT::getLabel => self::TRANSCRIPT->value,
            self::MEMORANDUM::getLabel => self::MEMORANDUM->value,
            self::LETTER::getLabel => self::LETTER->value
        };
    }
}
