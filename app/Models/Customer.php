<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, softDeletes;

    protected $guarded = [];

    protected $appends = ['full_name'];

    public function getFullNameAttribute(){
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

}
