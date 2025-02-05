<?php

namespace App\Models;

use Exception;
use Filament\Panel;
use App\Models\Team;
use App\Enums\RoleEnum;
use App\Models\Courier;
use App\Models\Setting;
use App\Models\CourierUser;
use Illuminate\Support\Str;
use App\Models\UserFunction;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\HasAvatar;
use Filament\Notifications\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Database\Eloquent\Builder;
use Filament\Models\Contracts\FilamentUser;
use App\Notifications\UserCreatedNotification;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Yebor974\Filament\RenewPassword\Contracts\RenewPasswordContract;
use Filament\Notifications\Auth\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable implements FilamentUser, RenewPasswordContract, HasAvatar
{
    use HasUuids, HasApiTokens, HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_number',
        'name',
        'phone',
        'email',
        'password',
        'user_function_id',
        'email_verified_at',
        'is_active',
        'avatar_url',
        'fcm_token',
        'signing_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'signing_code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'signing_code' => 'encrypted',
    ];

    protected $appends = [
        'identifier',
    ];


    public function firebaseNotifications()
    {
        return $this->hasMany(FirebaseNotification::class, 'notifiable_id');
    }

    public function userFunction()
    {
        return $this->belongsTo(UserFunction::class);
    }

    public function passwordResetCodes()
    {
        return $this->hasMany(PasswordResetCode::class);
    }

    public function managedTeams()
    {
        return $this->hasMany(Team::class, 'user_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }

    public function userTeams()
    {
        return $this->hasMany(TeamUser::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? asset("storage/$this->avatar_url") : null;
    }


    public function needRenewPassword(): bool
    {
        $resetPasswordDelay = Setting::where([
            ['key', 'password_expiration_delay']
        ])?->first()?->value ?? config("app.password_expiration_delay");
        return Carbon::parse($this->password_changed_at ?? $this->created_at)->addDays($resetPasswordDelay) < now();
    }

    public function getIdentifierAttribute()
    {
        return "{$this->name} - {$this->email}";
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'created_by');
    }

    public function initiatedDocuments()
    {
        $initiatorRoleId = CustomRole::firstWhere('name', RoleEnum::INITIATOR->getLabel())?->id;

        return $this->hasMany(DocumentUser::class, 'user_id')
            ->where('role_id', $initiatorRoleId);
    }

    public function docsCreated()
    {
        return $this->hasMany(Document::class);
    }

    public function createdCouriers()
    {
        return $this->belongsToMany(Courier::class, 'created_by');
    }

    public function deliveries()
    {
        return $this->hasMany(CourierUser::class, 'user_id');
    }

    public function docValidationHistory()
    {
        return $this->hasMany(DocValidationHistory::class);
    }

    public function lastDocValidationHistory(Document $document)
    {
        return $document->validationHistory()
            ->where('user_id', $this->id)
            ->latest()
            ?->first();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }


    public static function generatePassword()
    {
        // Generate random string and encrypt it.
        return bcrypt(Str::random(35));
    }


    public function scopeWithoutRoles(Builder $query, $roles, $guard = null): Builder
    {
        if ($roles instanceof Collection) {
            $roles = $roles->all();
        }

        if (!is_array($roles)) {
            $roles = [$roles];
        }

        $roles = array_map(function ($role) use ($guard) {
            if ($role instanceof CustomRole) {
                return $role;
            }

            $method = is_numeric($role) ? 'findById' : 'findByName';
            $guard = $guard ?: $this->getDefaultGuardName();

            return CustomRole::{$method}($role, $guard);
        }, $roles);

        return $query->whereHas('roles', function ($query) use ($roles) {
            $query->where(function ($query) use ($roles) {
                foreach ($roles as $role) {
                    $query->where(config('permission.table_names.roles') . '.id', '!=', $role->id);
                }
            });
        });
    }

    public function scopeHaveCourierRole($query, bool $bool)
    {
        return $query->whereHas('roles', function ($subQuery) use ($bool) {
            $subQuery->where('is_role_courier', $bool);
        });
    }

    public function scopeActive($query, $bool)
    {
        return $query->where('is_active', $bool);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'registration_number',
                'name',
                'phone',
                'email',
                'password',
                'password_changed_at',
                'email_verified_at',
                'is_active',
                'signature',
            ]);
        // Chain fluent methods for configuration options
    }


    public static function sendPasswordResetLink($user)
    {
        $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
            ['email' => $user->email],
            function (CanResetPassword $user, string $token): void {
                if (! method_exists($user, 'notify')) {
                    $userClass = $user::class;

                    throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
                }

                $notification = new ResetPasswordNotification($token);
                $notification->url = Filament::getResetPasswordUrl($token, $user);

                $user->notify($notification);
            },
        );

        if ($status !== Password::RESET_LINK_SENT) {
            Notification::make()
                ->title(__($status))
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title(__('Un mail a été envoyé à ' . $user->email))
            ->success()
            ->send();
    }


    public static function sendSetPasswordLink($user)
    {
        try {
            $status = Password::broker(Filament::getAuthPasswordBroker())->sendResetLink(
                ['email' => $user->email],
                function ($user, string $token): void {
                    if (!method_exists($user, 'notify')) {
                        $userClass = $user::class;
                        throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
                    }

                    $notification = new UserCreatedNotification($token);

                    $user->notify($notification);
                },
            );

            if ($status !== Password::RESET_LINK_SENT) {
                Notification::make()
                    ->title(__($status))
                    ->danger()
                    ->send();

                return;
            }

            Notification::make()
                ->title(__('Un mail a été envoyé à ' . $user->email))
                ->success()
                ->send()
                ->persistent();
        } catch (\Exception $e) {
            // Notify the user of the failure
            Notification::make()
                ->title(__('There was an issue sending the email. Please try again later.'))
                ->body($e->getMessage())
                ->danger()
                ->send()
                ->persistent();
        }
    }

}
