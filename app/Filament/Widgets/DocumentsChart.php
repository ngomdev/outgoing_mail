<?php

namespace App\Filament\Widgets;

use App\Enums\RoleEnum;
use App\Enums\DocStatus;
use App\Models\Document;
use Illuminate\Support\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class DocumentsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    private $startDate;

    private $endDate;
    protected static ?string $heading = 'Documents';

    protected static ?int $sort = -2;

    protected static ?string $pollingInterval = '10s';

    protected static bool $isLazy = false;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $this->startDate = Carbon::createFromFormat('Y-m-d', $this->filters['start_date'])->startOfDay();

        $this->endDate = Carbon::createFromFormat('Y-m-d', $this->filters['end_date'])->endOfDay();

        $auth = auth()->user();

        $query = Document::query();

        if (!$auth->hasAnyRole([RoleEnum::RES_SUIVI->getLabel(), RoleEnum::AG->getLabel(), RoleEnum::ADMIN->getLabel(), RoleEnum::SUPER_ADMIN->getLabel()])) {
            $query = $query->whereHas('documentUsers', function ($q) use ($auth, $query) {
                $q->where('user_id', $auth->id);
            });
        }

        $countsPerStatus = $query->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', '!=', DocStatus::CANCELLED)
            ->groupBy('status')
            ->selectRaw('status, count(*) as count')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Documents',
                    'data' => $countsPerStatus->map(fn ($item) => $item->count),
                    'backgroundColor' => $countsPerStatus->map(fn ($item) => $item->status->getRgbColor()),
                ],
            ],
            'labels' => $countsPerStatus->map(fn ($item) => $item->status->getLabel()),
            'hoverOffset' => 4
        ];
    }

    public function getDescription(): ?string
    {
        $formattedStartDate = $this->startDate->translatedFormat('d M Y');
        $formattedEndDate = $this->endDate->translatedFormat('d M Y');

        return "Reporting documents du $formattedStartDate au $formattedEndDate";
    }

    protected function getType(): string
    {
        return 'doughnut';
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
        return auth()->user()->can(['view_any_document::module::document', 'view_document::module::document']);
    }
}
