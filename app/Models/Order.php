<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'postal_code',
        'pref_id',
        'address1',
        'address2',
        'phone_number',
        'shipping_fee',
    ];

    /**
     * 会員氏名で検索するクエリのスコープを設定する。
     *
     * @param Builder $query
     * @param string|null $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if ($search !== null) {
            $converted = mb_convert_kana($search, 's');
            $searchSplited = preg_split('/[\s]+/', $converted);
            foreach ($searchSplited as $value) {
                $query->whereHas(
                    'user',
                    function ($query) use ($value) {
                        $query->where('name', 'like', '%' . $value . '%');
                    }
                );
            }
        }
        return $query;
    }

    /**
     * 注文を所有している会員を取得する。
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 注文の全ての注文詳細を取得する。
     *
     * @return HasMany
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * 注文を所有しているエリアを取得する。
     *
     * @return BelongsTo
     */
    public function pref(): BelongsTo
    {
        return $this->belongsTo(Pref::class);
    }
}
