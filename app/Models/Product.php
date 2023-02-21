<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;
use App\Traits\Search;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use Search;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'image',
        'introduction',
        'price',
        'stock',
        'start_date',
        'end_date',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    /**
     * 商品の全ての注文詳細を取得する。
     *
     * @return HasMany
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * 販売中の商品のみを含むようにクエリのスコープを設定する。
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnSale(Builder $query): Builder
    {
        return $query->where('start_date', '<=', Carbon::now())->where(function ($query) {
            $query->where('end_date', '>=', Carbon::now())->orWhereNull('end_date');
        });
    }
}
