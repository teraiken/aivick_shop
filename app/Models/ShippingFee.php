<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Area;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * 送料を所有しているエリアを取得する。
     *
     * @return BelongsTo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
