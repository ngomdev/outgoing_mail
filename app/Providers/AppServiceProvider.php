<?php

namespace App\Providers;

use Filament\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Support\Facades\URL;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentView;
use Illuminate\Validation\ValidationException;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\ViewDocument;
use Filament\View\PanelsRenderHook;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
            request()->server->set('HTTPS', request()->header('X-Forwarded-Proto', 'https') == 'https' ? 'on' : 'off');
        }

        Table::configureUsing(function (Table $table): void {
            $table
                ->striped();
        });

        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };

        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_HEADER_ACTIONS_BEFORE,
            fn() => Blade::render('@livewire(\'document-module.doc-history\')'),
            scopes: ViewDocument::class,
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn(): View => view('tos'),
        );

    }
}
