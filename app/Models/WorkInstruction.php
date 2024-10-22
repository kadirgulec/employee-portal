<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WorkInstruction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['confirmed_at', 'last_reminder_email_at']);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
