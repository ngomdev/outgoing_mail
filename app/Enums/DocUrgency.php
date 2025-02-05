<?php

namespace App\Enums;

use App\Models\Setting;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;


enum DocUrgency: string implements HasLabel, HasColor, HasIcon
{
    case NORMAL = 'normal';
    case URGENT = 'urgent';
    case CRITICAL = 'critical';

    public function getLabel(): ?string
    {
        $options = Setting::whereIn('key', [
            SettingKeys::DOC_URGENCY_NORMAL->value,
            SettingKeys::DOC_URGENCY_URGENT->value,
            SettingKeys::DOC_URGENCY_CRITICAL->value
        ])
            ->where('is_active', true)
            ->get();

        $normalValue = $options
            ->where('key', SettingKeys::DOC_URGENCY_NORMAL->value)
            ->first()?->value ??
            config("app.doc_urgency_normal");

        $urgentValue = $options
            ->where('key', SettingKeys::DOC_URGENCY_URGENT->value)
            ->first()?->value ??
            config("app.doc_urgency_urgent");

        $criticalValue = $options
            ->where('key', SettingKeys::DOC_URGENCY_CRITICAL->value)
            ->first()?->value ??
            config("app.doc_urgency_critical");

        return match ($this) {
            self::NORMAL => "Normal ($normalValue H)",
            self::URGENT => "Urgent ($urgentValue H)",
            self::CRITICAL => "Très urgent ($criticalValue H)"
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::NORMAL => 'success',
            self::URGENT => 'warning',
            self::CRITICAL => 'danger'
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::NORMAL => 'heroicon-m-clock',
            self::URGENT => 'heroicon-m-clock',
            self::CRITICAL => 'heroicon-m-clock'
        };
    }

    public function getValue()
    {
        $options = Setting::whereIn('key', [
            SettingKeys::DOC_URGENCY_NORMAL->value,
            SettingKeys::DOC_URGENCY_URGENT->value,
            SettingKeys::DOC_URGENCY_CRITICAL->value
        ])
            ->where('is_active', true)
            ->get();

        $normalValue = $options
            ->where('key', SettingKeys::DOC_URGENCY_NORMAL->value)
            ->first()?->value ??
            config("app.doc_urgency_normal");

        $urgentValue = $options
            ->where('key', SettingKeys::DOC_URGENCY_URGENT->value)
            ->first()?->value ??
            config("app.doc_urgency_urgent");

        $criticalValue = $options
            ->where('key', SettingKeys::DOC_URGENCY_CRITICAL->value)
            ->first()?->value ??
            config("app.doc_urgency_critical");

        return match ($this) {
            self::NORMAL => $normalValue,
            self::URGENT => $urgentValue,
            self::CRITICAL => $criticalValue
        };
    }

    public static function getValues(): array
    {
        return [
            self::NORMAL,
            self::URGENT,
            self::CRITICAL
        ];
    }
}
