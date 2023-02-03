<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShippingFee;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function latestShippingFee()
    {
        return $this->hasOne(ShippingFee::class)->whereNull('end_date');
    }
}
