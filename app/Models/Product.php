<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'introduction',
        'price',
        'stock',
        'status',
    ];

    public function scopeSearch($query, $search)
    {
        if ($search !== null) {
            $converted = mb_convert_kana($search, 's');
            $searchSplited = preg_split('/[\s]+/', $converted);
            foreach ($searchSplited as $value) {
                $query->where('name', 'like', '%' .$value. '%');
            }
        }
        return $query;
    }
}
