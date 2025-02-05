<?php

namespace App\Filament\Widgets;

use App\Models\CourierUser;
use App\Enums\CourierStatus;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class CouriersChart extends ChartWidget
{

    use InteractsWithPageFilters;

    private $startDate;

    private $endDate;
    protected static ?string $heading = 'Courriers';

    protected static ?int $sort = -1;

    protected static ?string $pollingInterval = '10s';

    protected static bool $isLazy = false;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {

        $this->startDate = Carbon::createFromFormat('Y-m-d', $this->filters['start_date'])->startOfDay();

        $this->endDate = Carbon::createFromFormat('Y-m-d', $this->filters['end_date'])->endOfDay();

        $countsCourierByStatus = CourierUser::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status','!=', CourierStatus::CANCELLED)
            ->groupBy('status')
            ->selectRaw('status,count(*) as count')
            ->pluck('count', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'Couriers',
                    'data' => $countsCourierByStatus->values()->all(),
                    'backgroundColor' => $countsCourierByStatus->keys()->map(function ($status) {
                        return CourierStatus::from($status)->getRgbColor();
                    })->all(),
                ],
            ],
            'labels' => $countsCourierByStatus->keys()->map(function ($status) {
                return CourierStatus::from($status)->getLabel();
            })->all(),
            'hoverOffset' => 4
        ];
    }

    public function getDescription(): ?string
    {
        $formattedStartDate = $this->startDate->translatedFormat('d M Y');
        $formattedEndDate = $this->endDate->translatedFormat('d M Y');

        return "Reporting couriers du $formattedStartDate au $formattedEndDate";
    }

    protected function getType(): string
    {
        return 'polarArea';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
        ];
    }


    public static function canView(): bool
    {
        return auth()->user()->can(['view_any_courier::module::courier', 'view_courier::module::courier']);
    }
}
