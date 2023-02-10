<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;
use App\Traits\Search;
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

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
