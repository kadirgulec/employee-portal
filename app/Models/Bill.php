<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


//TODO properties must be written
/**
 * @property Carbon $date
 * @property Customer $customer
 * @property HasMany $positions
 * @property float $cost_approval
 *
 *
 */
class Bill extends Model
{
    use HasFactory, softDeletes, LogsActivity;
    protected $guarded = [ ];

    protected $casts = [
        'date' => 'date',
    ];

    protected $appends = ['total_price'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });
        static::softDeleted(function ($model) {
            $model->deleted_by = auth()->user()->id;
        });
    }


    public function getTotalPriceAttribute(): string
    {
        $totalPrice = 0;
        foreach ($this->positions as $position) {
            $totalPrice += $position->product_price * $position->quantity;
        }
        return $totalPrice;
    }
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
