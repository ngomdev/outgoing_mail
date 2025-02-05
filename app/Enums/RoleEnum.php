<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RoleEnum: string implements HasLabel
{
    case SUPER_ADMIN = 'Super Admin';
    case ADMIN = 'Admin';
    case RES_SUIVI = 'res_suivi';
    case RES_JURI = 'res_juri';
    case RES_ACHAT = 'res_achat';
    case AG = 'admin_general';
    case RH = 'res_humaine';
    case PARAPHEUR = 'parapheur';
    case SIGN_MAIN = 'sign_main';
    case SIGN_ORDER = 'sign_order';
    case SIGN_INTERIM = 'sign_interim';
    case SIGN_DELEGATION = 'sign_delegation';
    case COURSER = 'courser';
    case INITIATOR = 'initiator';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super Admin',
            self::ADMIN => 'Admin',
            self::RES_SUIVI => 'Responsable suivi',
            self::RES_JURI => 'Responsable juridique',
            self::RES_ACHAT => 'Responsable achat',
            self::AG => 'Administrateur général',
            self::RH => 'Responsable RH',
            self::PARAPHEUR => 'Parapheur',
            self::SIGN_MAIN => 'Signataire principal',
            self::SIGN_ORDER => 'Signataire par ordre',
            self::SIGN_INTERIM => 'Signataire par interim',
            self::SIGN_DELEGATION => 'Signataire par délégation',
            self::COURSER => 'Coursier',
            self::INITIATOR => 'Initiateur',
        };
    }
}
