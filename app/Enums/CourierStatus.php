<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Colors\Color;

/**
 * The CourStatus enum.
 *
 * @method static self CourStatus()
 */

enum CourierStatus: string implements HasLabel, HasColor
{

    case DRAFT = 'draft';
    case INITIATED = 'initialized';
    case ASSIGNED = 'assigned';
    case RETRIEVED = 'retrieved';
    case REJECTED = 'rejected';
    case DELIVERED = 'delivered';
    case NOT_DELIVERED = 'not_delivered';
    case CLOSED = 'closed';
    case ARCHIVED = 'archived';
    case CANCELLED = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => 'En préparation',
            self::INITIATED => 'Attente levée',
            self::ASSIGNED => 'Assigné',
            self::RETRIEVED => 'Levé',
            self::REJECTED => 'Rejeté',
            self::DELIVERED => 'Distribué',
            self::NOT_DELIVERED => 'Non distribué',
            self::CLOSED => 'Fermé',
            self::ARCHIVED => 'Archivé',
            self::CANCELLED => 'Annulé',

        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::INITIATED => 'secondary',
            self::ASSIGNED => 'primary',
            self::RETRIEVED => 'warning',
            self::DELIVERED => 'success',
            self::NOT_DELIVERED => 'danger',
            self::REJECTED => 'danger',
            self::CLOSED => 'black',
            self::ARCHIVED => 'info',
            self::CANCELLED => 'danger',
        };
    }

    public function getRgbColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'rgb(211, 211, 211)',
            self::INITIATED => 'rgb(173, 216, 230)',
            self::ASSIGNED => 'rgb(0, 123, 255)',
            self::RETRIEVED => 'rgb(255, 193, 7)',
            self::DELIVERED => 'rgb(40, 167, 69)',
            self::NOT_DELIVERED => 'rgb(220, 53, 69)',
            self::REJECTED => 'rgb(139, 0, 0)',
            self::CLOSED => 'rgb(95, 158, 160)',
            self::ARCHIVED => 'rgb(192, 192, 192)',
            default => null,
        };
    }


    public function getIcon(): ?string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-m-pencil',
            self::INITIATED => 'heroicon-m-check',
            self::ASSIGNED => 'heroicon-m-check',
            self::RETRIEVED => 'heroicon-m-pencil',
            self::DELIVERED => 'heroicon-m-check',
            self::NOT_DELIVERED => 'heroicon-m-x-mark',
            self::REJECTED => 'heroicon-m-archive-box-arrow-down',
            self::CLOSED => 'heroicon-m-archive-box-arrow-down',
            self::ARCHIVED => 'heroicon-m-archive-box-arrow-down',
            self::CANCELLED => 'heroicon-m-x-circle',
        };
    }

    public function getRank(): ?int
    {
        return match ($this) {
            self::DRAFT => 1,
            self::INITIATED => 2,
            self::ASSIGNED => 3,
            self::RETRIEVED => 4,
            self::DELIVERED => 5,
            self::NOT_DELIVERED => 6,
            self::REJECTED => 7,
            self::CLOSED => 8,
            self::ARCHIVED => 9,
            self::CANCELLED => 10,
        };
    }
}
