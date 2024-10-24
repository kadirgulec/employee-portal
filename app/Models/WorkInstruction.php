<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkInstruction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    //attributes
    public function getStatusAttribute(): string
    {
        if ($this->users()->wherePivot('user_id', auth()->id())->wherePivot('confirmed_at', null)->exists()) {
            return 'rejected';
        } elseif ($this->users()->wherePivot('user_id', auth()->id())->exists()) {
            return 'confirmed';
        } elseif ($this->created_at->isToday() || $this->created_at->isYesterday()) {
            return 'new';
        } elseif ($this->updated_at->isToday() || $this->updated_at->isYesterday()) {
            return 'updated';
        } elseif ($this->created_at->isLastWeek()) {
            return 'waiting';
        } else {
            return 'warning';
        }
    }


    //relations
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['confirmed_at', 'last_reminder_email_at']);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }
}
