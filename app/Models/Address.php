<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
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
    ];

    /**
     * 配送先を所有している会員を取得する。
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 配送先を所有している都道府県を取得する。
     *
     * @return BelongsTo
     */
    public function pref(): BelongsTo
    {
        return $this->belongsTo(Pref::class);
    }
}
