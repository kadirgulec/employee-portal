<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class IllnessNotification extends Model
{
    use HasFactory, SoftDeletes;

    public string $first_name;
    public string $last_name;
    protected $guarded= [];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function reportedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_to')
            ->where('illness_notification_contact', true);
    }

}
