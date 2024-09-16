<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasName
{
    use HasFactory, Notifiable, softDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded= [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['full_name'];


    public function getFullNameAttribute(): string
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if($this->avatar === null){
            return null;
        }
        return asset('storage') . $this->avatar;
    }

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

    public function department_user()
    {
        return $this->hasMany(DepartmentUser::class);

    }

    public function departments()
    {
        return $this->belongsToMany(Department::class)
            ->withPivot('leader');
    }

    public function illness_notifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(IllnessNotification::class);
    }

    public function filteredPermissions()
    {
       return $this->permissions()->where('name', 'like', "%.users.%");
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
