<?php

namespace App\Traits;

trait Search
{
    public function scopeSearch($query, $search)
    {
        if ($search !== null) {
            $converted = mb_convert_kana($search, 's');
            $searchSplited = preg_split('/[\s]+/', $converted);
            foreach ($searchSplited as $value) {
                $query->where('name', 'like', '%' . $value . '%');
            }
        }
        return $query;
    }
}
