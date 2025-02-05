<?php

namespace App\Providers\Filament;

use Filament\Panel;
use App\Enums\DocStatus;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use App\Filament\Pages\CustomLogin;
use App\Livewire\CustomPersonalInfo;
use Illuminate\Support\Facades\Blade;
use App\Filament\Widgets\AccountWidget;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\NavigationGroup;
use Illuminate\Validation\Rules\Password;
use App\Http\Middleware\CheckInactiveUser;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Pages\Auth\PasswordReset\ResetPassword;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Yebor974\Filament\RenewPassword\RenewPasswordPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Filament\Resources\DocumentModule\DocumentResource;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Filament\Resources\CourierModule\CourierResource\Pages\CreateCourier;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\EditDocument;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\ViewDocument;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\ListDocuments;
use App\Filament\Resources\DocumentModule\DocumentResource\Pages\CreateDocument;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->darkMode(false)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandName('Orbus Courrier')
            ->brandLogo(asset('images/logo-transparent.svg'))
            ->brandLogoHeight('4rem')
            ->id('admin')
            ->path('')
            ->login()
            ->colors(
                [
                    'danger' => Color::Rose,
                    'gray' => Color::Gray,
                    'info' => Color::Blue,
                    'primary' => Color::Cyan,
                    'secondary' => Color::Indigo,
                    'success' => Color::Emerald,
                    'warning' => Color::Yellow,
                    'amberLight' => '#facc15'
                ]
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages(
                [
                ]
            )
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets(
                [
                    AccountWidget::class,
                ]
            )
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->middleware(
                [
                    EncryptCookies::class,
                    AddQueuedCookiesToResponse::class,
                    StartSession::class,
                    AuthenticateSession::class,
                    ShareErrorsFromSession::class,
                    VerifyCsrfToken::class,
                    SubstituteBindings::class,
                    DisableBladeIconComponents::class,
                    DispatchServingFilamentEvent::class,
                    CheckInactiveUser::class,
                ]
            )
            // ->authMiddleware(
            //     [
            //         Authenticate::class,
            //     ]
            // )
            ->passwordReset(
                resetAction: ResetPassword::class
            )
            ->emailVerification()
            ->plugins(
                [
                    BreezyCore::make()
                        ->passwordUpdateRules(
                            rules: [Password::default()->mixedCase()->symbols()], // you may pass an array of validation rules as well. (default = ['min:8'])
                            requiresCurrentPassword: true, // when false, the user can update their password without entering their current password. (default = true)
                        )
                        ->myProfile(
                            hasAvatars: true,
                        )
                        ->avatarUploadComponent(
                            fn($fileUpload) => $fileUpload
                                ->disableLabel()
                                ->disk('public')
                                ->placeholder(
                                    fn() => Blade::render(
                                        "Glissez la photo de profile ici ou <span class='filepond--label-action'>Parcourir</span>"
                                    )
                                )
                                ->getUploadedFileNameForStorageUsing(
                                    function (TemporaryUploadedFile $file): string {
                                        $originalName = $file->getClientOriginalName();
                                        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                                        return (string) "user-uploads/" . auth()->id() . "/avatar_url." . $extension;
                                    }
                                )
                        )
                        ->myProfileComponents(
                            [
                                'personal_info' => CustomPersonalInfo::class,
                            ]
                        ),
                    FilamentShieldPlugin::make(),
                    RenewPasswordPlugin::make()
                        ->timestampColumn('password_changed_at')
                ]
            )
            ->navigationGroups(
                [
                    NavigationGroup::make()
                        ->label(fn(): string => __('Gestion Utilisateurs')),
                    NavigationGroup::make()
                        ->label(fn(): string => __('Production Documents')),
                    NavigationGroup::make()
                        ->label(fn(): string => __('Validation Documents')),
                    NavigationGroup::make()
                        ->label(fn(): string => __('Gestion Courriers')),
                    NavigationGroup::make()
                        ->label(fn(): string => __('Paramètres'))
                        ->icon('heroicon-o-cog'),
                    NavigationGroup::make()
                        ->label(fn(): string => __('Autres'))
                        ->icon('heroicon-o-hashtag'),
                ]
            )
            ->navigationItems(
                [
                    NavigationItem::make('docs_list')
                        ->label(fn(): string => __('Documents à traiter'))
                        ->url(fn(): string => ListDocuments::getUrl())
                        ->icon('heroicon-o-document-text')
                        ->group(fn(): string => __('Production Documents'))
                        ->badge(fn() => DocumentResource::getEloquentQuery()->where('status', '!=', DocStatus::CANCELLED)->count())
                        ->isActiveWhen(fn() => request()->routeIs([ListDocuments::getRouteName(), ViewDocument::getRouteName(), EditDocument::getRouteName()]))
                        ->visible(fn(): bool => auth()->user()->can('view_document::module::document'))
                        ->sort(1),
                    NavigationItem::make('create_doc')
                        ->label(fn(): string => __('Nouveau document'))
                        ->url(fn(): string => CreateDocument::getUrl())
                        ->icon('heroicon-o-plus-circle')
                        ->group(fn(): string => __('Production Documents'))
                        ->isActiveWhen(fn() => request()->routeIs(CreateDocument::getRouteName()))
                        ->visible(fn(): bool => auth()->user()->can('create_document::module::document'))
                        ->sort(4),
                    NavigationItem::make('create_courier')
                        ->label(fn(): string => __('Nouveau courrier'))
                        ->url(fn(): string => CreateCourier::getUrl())
                        ->icon('heroicon-o-plus-circle')
                        ->group(fn(): string => __('Gestion Courriers'))
                        ->isActiveWhen(fn() => request()->routeIs(CreateCourier::getRouteName()))
                        ->visible(fn(): bool => auth()->user()->can('create_courier::module::courier'))
                        ->sort(4),
                    NavigationItem::make('Politique de confidentialité')
                        ->url(config('app.tos_url'), shouldOpenInNewTab: true)
                        ->icon('heroicon-o-shield-exclamation')
                        ->group('Autres')
                        ->sort(3),
                ]
            )

            ->sidebarCollapsibleOnDesktop()
            ->font('Poppins')
            ->spa();
    }
}
