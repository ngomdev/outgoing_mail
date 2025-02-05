<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SettingKeys: string implements HasLabel
{
    case PASSWORD_EXPIRATION_DELAY = 'password_expiration_delay';
    case COURIER_RECOVERY_DELAY = 'courier_recovery_delay';
    case DOC_URGENCY_NORMAL = 'doc_urgency_normal';
    case DOC_URGENCY_URGENT = 'doc_urgency_urgent';
    case DOC_URGENCY_CRITICAL = 'doc_urgency_critical';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::PASSWORD_EXPIRATION_DELAY => 'documents',
            self::COURIER_RECOVERY_DELAY => 'courier_recovery_delay',
            self::DOC_URGENCY_NORMAL => 'doc_urgency_normal',
            self::DOC_URGENCY_URGENT => 'doc_urgency_urgent',
            self::DOC_URGENCY_CRITICAL => 'doc_urgency_critical'
        };
    }
}
