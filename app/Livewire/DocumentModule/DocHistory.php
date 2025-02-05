<?php

namespace App\Livewire\DocumentModule;

use Livewire\Component;
use App\Enums\DocStatus;
use App\Models\Document;
use Filament\Forms\Form;
use Livewire\WithPagination;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Spatie\Activitylog\Models\Activity;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\CanPaginateRecords;
use Noxo\FilamentActivityLog\Pages\Concerns\HasLogger;
use Noxo\FilamentActivityLog\Pages\Concerns\UrlHandling;
use Noxo\FilamentActivityLog\Pages\Concerns\HasListFilters;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Illuminate\Database\Eloquent;


class DocHistory extends Component implements HasForms
{
    use CanPaginateRecords;
    use HasListFilters;
    use HasLogger;
    use UrlHandling;
    use InteractsWithForms;
    use WithPagination;

    public Document $document;

    public $version;

    public bool $isCollapsible = true;

    public bool $isCollapsed = true;

    public int $historyCount = 0;

    public function mount()
    {
        $docId = Route::current()->parameters()['record'];
        $this->document = Document::findOrFail($docId);

        if ($this->document) {
            $query = $this->document->docHistory();
            $this->version = $query->latest()?->first()?->version ?? 0.1;
            $this->historyCount = $query->count();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Section::make()
                        ->compact()
                        ->columns(3)
                        ->schema(
                            [
                                $this->getDateRangeField(),
                                $this->getCauserField(),
                                $this->getEventField(),
                            ]
                        ),
                ]
            )
            ->debounce();
    }

    protected function getDateRangeField()
    {
        return DateRangePicker::make('date_range')
            ->useRangeLabels()
            ->alwaysShowCalendar(false)
            ->label(__('filament-activity-log::activities.filters.date'))
            ->placeholder(__('filament-activity-log::activities.filters.date'));
    }

    protected function getCauserField()
    {
        return Select::make('causer')
            ->label(__('filament-activity-log::activities.filters.causer'))
            ->native(false)
            ->allowHtml()
            ->options(
                function () {
                    $causers = Activity::query()
                        ->groupBy('causer_id', 'causer_type')
                        ->get(['causer_id', 'causer_type'])
                        ->map(
                            fn ($activity) => [
                                'value' => "{$activity->causer_type}:{$activity->causer_id}",
                                'label' => Blade::render(
                                    '<x-filament::avatar
                                    src="' . filament()->getUserAvatarUrl($activity->causer) . '"
                                size="w-8 h-8"
                                class="inline mr-2"
                                /> ' . $activity->causer?->name
                                ),
                            ]
                        )
                        ->pluck('label', 'value');

                    return $causers;
                }
            );
    }

    protected function getEventField()
    {
        return Select::make('event')
            ->label(__('filament-activity-log::activities.filters.event'))
            ->visible(fn (callable $get) => $get('subject_type'))
            ->native(false)
            ->options(
                function (callable $get) {
                    $events = Activity::query()
                        ->where('subject_type', $get('subject_type'))
                        ->groupBy('event')
                        ->pluck('event')
                        ->map(
                            fn ($event) => [
                                'value' => $event,
                                'label' => __("filament-activity-log::activities.events.{$event}.title"),
                            ]
                        )
                        ->pluck('label', 'value');

                    return $events;
                }
            );
    }

    public function getActivities()
    {
        return $this->paginateTableQuery(
            $this->applyFilters(
                Activity::where(
                    [
                        ['subject_type', 'App\Models\Document'],
                        ['subject_id', $this->document->id]
                    ]
                )
                    ->latest()
            )
        );
    }

    public function applyFilters(Eloquent\Builder $query): Eloquent\Builder
    {
        $state = $this->form->getState();
        $causer = with(
            $state['causer'],
            function ($causer) {
                if (empty($causer) || !str_contains($causer, ':')) {
                    return null;
                }

                $parts = explode(':', $causer);
                if (count($parts) !== 2) {
                    return null;
                }

                [$causer_type, $causer_id] = $parts;

                return compact('causer_type', 'causer_id');
            }
        );

        $query
            ->when(
                $date_range = $this->getDateRange($state['date_range'] ?? null),
                fn (Eloquent\Builder $query) => $query->whereBetween('created_at', $date_range)
            )
            ->unless(
                empty($causer),
                fn (Eloquent\Builder $query) => $query->where($causer)
            )
            ->unless(
                empty($state['subject_type']),
                fn (Eloquent\Builder $query) => $query->where('subject_type', $state['subject_type'])
            )
            ->unless(
                empty($state['subject_id']),
                fn (Eloquent\Builder $query) => $query->where('subject_id', $state['subject_id'])
            )
            ->unless(
                empty($state['event']),
                fn (Eloquent\Builder $query) => $query->where('event', $state['event'])
            );

        return $query;
    }

    protected function getIdentifiedTableQueryStringPropertyNameFor(string $property): string
    {
        return $property;
    }

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 10;
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 50];
    }

    public function getWidgetData(): array
    {
        return [];
    }

    public function getCachedSubNavigation()
    {
    }

    public function getRenderHookScopes()
    {
    }

    public function getHeader()
    {
    }

    public function getHeading()
    {
    }

    public function getVisibleHeaderWidgets()
    {
    }

    public function getVisibleFooterWidgets()
    {
    }

    public function getFooter()
    {
    }

    public function isVisible(): bool
    {
        return $this->document->status !== DocStatus::DRAFT;
    }


    public function render()
    {
        return view('livewire.document-module.doc-history');
    }
}
