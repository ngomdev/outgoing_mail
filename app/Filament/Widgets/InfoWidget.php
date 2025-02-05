<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class InfoWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected static ?int $sort = 2;

    protected static string $view = 'filament.widgets.info-widget';
}
