<?php

namespace App\Filament\Pages;

use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Noxo\FilamentActivityLog\Pages\ListActivities;


class ActivityLog extends ListActivities
{
    protected bool $isCollapsible = true;

    protected bool $isCollapsed = true;

    protected static ?int $navigationSort = 2;

    protected static bool $shouldRegisterNavigation = false;


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->compact()
                    ->columns(3)
                    ->schema([
                        $this->getDateRangeField(),
                        $this->getCauserField(),
                        $this->getSubjectTypeField(),
                    ]),
                Section::make()
                    ->compact()
                    ->columns(2)
                    ->schema([
                        $this->getSubjectIDField(),
                        $this->getEventField(),
                    ])
                    ->visible(fn(Get $get) => $get('subject_type')),
            ])
            ->debounce()
            ->statePath('filters');
    }


    public function getTitle(): string
    {
        return __('filament-activity-log::activities.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament-activity-log::activities.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Paramètres');
    }
}
