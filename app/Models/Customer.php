<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, softDeletes;

    protected $guarded = [];

    protected $appends = ['full_name'];

    public string $first_name;
    public string $last_name;

    public function getFullNameAttribute(): string
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

}
