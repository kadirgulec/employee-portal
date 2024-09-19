<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use HasFactory, softDeletes;

    protected $fillable = [
        'date',
        'customer_id',
    ];

    protected $appends = ['total_price'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
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
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function positions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Position::class);
    }
}
