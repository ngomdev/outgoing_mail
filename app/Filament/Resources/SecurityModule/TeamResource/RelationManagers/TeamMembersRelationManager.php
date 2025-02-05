<?php

namespace App\Filament\Resources\SecurityModule\TeamResource\RelationManagers;

use Filament\Forms;
use App\Models\Team;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;

class TeamMembersRelationManager extends RelationManager
{
    protected static string $relationship = 'members';
    protected static ?string $title = 'Membres entité';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('registration_number')
                    ->label('Matricule')
                    ->formatStateUsing(fn ($state) => new HtmlString("<p class='font-semibold'>{$state}</p>")),
                ViewColumn::make('user')
                    ->label('Nom')
                    ->view('filament.tables.columns.user-info'),
                TextColumn::make('roles.name')
                    ->label('Profil')
                    ->badge()
                    ->color('primary'),
                ViewColumn::make('contact')
                    ->label('Contact')
                    ->view('filament.tables.columns.contact'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                    ->label('Retirer')
                    ->requiresConfirmation()
                    ->modalHeading('Retirer utilisateur')
                    ->modalDescription('Êtes-vous sur de vouloir retirer l\'utilisateur de l\'entité'),
                Action::make('transferMember')
                    ->requiresConfirmation()
                    ->label('Transférer')
                    ->icon('heroicon-m-arrow-uturn-right')
                    ->modalDescription(fn (User $record): string => "Êtes-vous sur de vouloir transfèrer $record->name dans une autre entité?")
                    ->form([
                        Select::make('team_id')
                            ->label('Entité')
                            ->options(function (RelationManager $livewire) {
                                $ownerRecord = $livewire->getOwnerRecord();
                                return Team::query()
                                    ->where('id', '!=', $ownerRecord->id)
                                    ->where('is_active', true)
                                    ->pluck('name', 'id');
                            })
                            ->required(),
                    ])
                    ->action(function (array $data, User $record, RelationManager $livewire): void {
                        // Detach user from this team
                        $ownerRecord = $livewire->getOwnerRecord();
                        $ownerRecord->members()->detach($record->id);

                        // Attach user to selected team
                        $selectedTeam = Team::find($data['team_id']);
                        $selectedTeam->members()->attach($record->id);

                        Notification::make()
                            ->success()
                            ->title(__('Succès !'))
                            ->body("Collaborateur ajouté à l'entité $selectedTeam->name")
                            ->persistent()
                            ->send();
                    })
            ]);
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->teamUsers->count();
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
