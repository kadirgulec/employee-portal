<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $city
 * @property string $email
 * @property string $mobile
 * @property string $phone
 *
 * @method static select(string $string)
 */
class Customer extends Model
{
    use HasFactory, softDeletes;

    protected $guarded = [];

    protected $appends = ['full_name'];

    // ATTRIBUTES
    public function getFullNameAttribute(): string
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    //RELATIONS

    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

}
