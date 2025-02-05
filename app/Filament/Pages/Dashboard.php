<?php

namespace App\Filament\Pages;

use Illuminate\Support\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Dashboard\Concerns\HasFiltersAction;
use Filament\Pages\Dashboard\Actions\FilterAction;


class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
    use HasFiltersAction;


    // Mount method to initialize the filter state
    public function mount(): void
    {
        $this->filters = [
            'start_date' => Carbon::now()->startOfYear()->format('Y-m-d'),
            'end_date' => Carbon::now()->endOfDay()->format('Y-m-d'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            FilterAction::make()
                ->form([
                    DatePicker::make('start_date')
                            ->label("Date début")
                            ->before('end_date')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->default(Carbon::now()->startOfYear()),

                        DatePicker::make('end_date')
                            ->label("Date fin")
                            ->after('start_date')
                            ->native(false)
                            ->displayFormat('d M Y')
                            ->prefixIcon('heroicon-m-calendar-days')
                            ->default(Carbon::now()->endOfDay()),
                ]),
        ];
    }
}
