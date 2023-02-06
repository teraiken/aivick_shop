<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Area;

class ShippingFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'fee',
        'start_date',
        'end_date',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
