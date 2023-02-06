<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShippingFee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function shippingFees()
    {
        return $this->hasMany(ShippingFee::class)->orderBy('id', 'desc');
    }

    public function currentShippingFee()
    {
        return $this->hasOne(ShippingFee::class)->where('start_date', '<', Carbon::now());
    }
}
