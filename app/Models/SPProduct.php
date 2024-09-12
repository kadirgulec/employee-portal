<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SPProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $table = 'sp_products';
    public function positions()
    {
        $this->hasMany(Position::class);
    }
}
