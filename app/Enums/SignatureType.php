<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SignatureType: string implements HasLabel
{
    case MAIN = 'main';
    case DELEGATION = 'delegation';
    case INTERIM = 'interim';
    case ORDER = 'order';
    case PARAPHE = 'paraphe';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::MAIN => 'Signature principale',
            self::DELEGATION => 'Signature délégation',
            self::INTERIM => 'Signature intérim',
            self::ORDER => 'Signature ordre',
            self::PARAPHE => 'Paraphe'
        };
    }

    public function getSignatureLabel(): ?string
    {
        return match ($this) {
            self::MAIN => 'Signature principale',
            self::DELEGATION => 'Signature délégation',
            self::INTERIM => 'Signature intérim',
            self::ORDER => 'Signature ordre'
        };
    }

    public static function getByValue($name): ?string
    {
        return match ($name) {
            self::MAIN->value => 'Signature principale',
            self::DELEGATION->value => 'Signature délégation',
            self::INTERIM->value => 'Signature intérim',
            self::ORDER->value => 'Signature ordre',
            self::PARAPHE->value => 'Paraphe'
        };
    }
}
