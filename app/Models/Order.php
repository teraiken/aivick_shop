<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderDetail;

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

    public function scopeSearch($query, $search)
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
