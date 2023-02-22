<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Search
{
    /**
     * 検索キーワードのみを含むようにクエリのスコープを設定する。
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
                $query->where('name', 'like', '%' . $value . '%');
            }
        }
        return $query;
    }
}
