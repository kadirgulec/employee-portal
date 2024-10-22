<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Department extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = [
        'name',
        'description',
    ];
    public array $translatable = ['name'];

    public function users(): HasMany
    {
        return $this->hasMany(DepartmentUser::class);

    }

    public function department_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('leader');
    }


}
