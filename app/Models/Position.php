<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Position extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    public function bill()
    {
        $this->belongsTo(Bill::class);
    }

    public function sp_product()
    {
        $this->belongsTo(SPProduct::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
