<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string $full_name
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar
 * @property $pin
 *
 */
class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    use HasFactory, Notifiable, softDeletes, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * Get the user's full name by combining first name and last name.
     *
     * @return string The full name with each word capitalized.
     */
    public function getFullNameAttribute(): string
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get the URL for the user's Filament avatar.
     *
     * If the user does not have an avatar, return null. Otherwise, return
     * the URL of the avatar stored in the 'storage' directory.
     *
     * @return string|null The avatar URL or null if no avatar is set.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        if ($this->avatar === null) {
            return null;
        }
        return asset('storage/' .$this->avatar);
    }

    /**
     * Get the user's full name for Filament.
     *
     * This returns the full name of the user.
     *
     * @return string The full name of the user.
     */
    public function getFilamentName(): string
    {
        return $this->full_name;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'settings' => 'array',
        ];
    }

    /**
     * Get the user's associated departments.
     *
     * Defines a one-to-many relationship between the user and department-user records.
     *
     * @return HasMany
     */
    public function department_user(): HasMany
    {
        return $this->hasMany(DepartmentUser::class);

    }

    /**
     * Get the departments the user belongs to.
     *
     * Defines a many-to-many relationship between the user and departments.
     * Includes the 'leader' pivot field for additional relationship data.
     *
     * @return BelongsToMany
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class)
            ->withPivot('leader');
    }

    /**
     * Get the illness notifications associated with the user.
     *
     * Defines a one-to-many relationship between the user and illness notifications.
     *
     * @return HasMany
     */
    public function illness_notifications(): HasMany
    {
        return $this->hasMany(IllnessNotification::class);
    }

    /**
     * Determine if the user can access the specified panel.
     *
     * This method checks if the user has an aks-service email.
     *
     * @param Panel $panel The panel instance to check access for.
     * @return bool True if the user can access the panel, otherwise false.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@aks-service.de');
    }
}
