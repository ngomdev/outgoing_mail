<?php

namespace App\Filament\Loggers;

use App\Enums\DocStatus;
use App\Models\Document;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Noxo\FilamentActivityLog\Loggers\Logger;
use Noxo\FilamentActivityLog\ResourceLogger\Field;
use App\Filament\Resources\DocumentModule\DocumentResource;
use Noxo\FilamentActivityLog\ResourceLogger\ResourceLogger;
use Noxo\FilamentActivityLog\ResourceLogger\RelationManager;

class DocumentLogger extends Logger
{
    public static ?string $model = Document::class;

    public static function getLabel(): string|Htmlable|null
    {
        return DocumentResource::getModelLabel();
    }

    public static function resource(ResourceLogger $logger): ResourceLogger
    {
        return $logger
            ->fields([
                Field::make('name')
                    ->label(__('Nom document')),

                Field::make('object')
                    ->label(__('Objet')),

                Field::make('doc_type')
                    ->label(__('Type document'))
                    ->hasMany('categories')
                    ->badge(),

                Field::make('status')
                    ->label(__('Statut'))
                    ->enum(DocStatus::class),

                Field::make('doc_path')
                    ->label(__('Fichier'))
                    ->formatStateUsing(fn(?string $state) => $state ? new HtmlString("<a target='_blank' class='fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-primary fi-btn-color-primary fi-size-sm fi-btn-size-sm gap-1.5 px-1 py-1 text-sm inline-grid fi-btn-outlined ring-1 text-primary-600 ring-primary-600 hover:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-500' href=" . asset('storage/' . $state) . ">
                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='w-4 h-4'>
                    <path stroke-linecap='round' stroke-linejoin='round' d='M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25' />
                  </svg>

                        Ouvrir document
                    </a>") : ''),

                Field::make('documentUsers.name')
                    ->hasMany('documentUsers')
                    ->label(__('Parapheurs & Signataires'))
                    ->badge(),
            ])
            ->relationManagers([
                RelationManager::make('documentUsers')
                    ->label(__('Parapheurs & Signataires'))
                    ->fields([
                        Field::make('name')
                            ->label(__('Nom')),

                        Field::make('role')
                            ->label(__('Rôle')),
                    ]),
            ]);
    }
}
