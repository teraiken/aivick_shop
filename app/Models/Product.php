<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;
use App\Traits\Search;

class Product extends Model
{
    use HasFactory;
    use Search;

    protected $fillable = [
        'name',
        'image',
        'introduction',
        'price',
        'stock',
        'status',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}
