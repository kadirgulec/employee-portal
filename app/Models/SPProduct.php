<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SPProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'sp_products';
    public function positions()
    {
        $this->hasMany(Position::class);
    }
}
