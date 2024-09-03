<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function invoice()
    {
        $this->belongsTo(Invoice::class);
    }

    public function sp_product()
    {
        $this->belongsTo(SPProduct::class);
    }
}
