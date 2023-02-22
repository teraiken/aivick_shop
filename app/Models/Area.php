<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShippingFee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * エリアの全ての送料を取得する。
     *
     * @return HasMany
     */
    public function shippingFees(): HasMany
    {
        return $this->hasMany(ShippingFee::class)->orderBy('id', 'desc');
    }

    /**
     * エリアの適応中の送料を取得する。
     *
     * @return HasOne
     */
    public function currentShippingFee(): HasOne
    {
        return $this->hasOne(ShippingFee::class)->where('start_date', '<=', Carbon::now())->where(function ($query) {
            $query->where('end_date', '>=', Carbon::now())->orWhereNull('end_date');
        });
    }

    /**
     * エリアの最新の送料を取得する。
     *
     * @return HasOne
     */
    public function latestShippingFee(): HasOne
    {
        return $this->hasOne(ShippingFee::class)->latestOfMany();
    }

    /**
     * エリアの全ての都道府県を取得する。
     *
     * @return HasMany
     */
    public function prefs(): HasMany
    {
        return $this->hasMany(Pref::class);
    }
}
