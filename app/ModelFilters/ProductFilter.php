<?php 

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class ProductFilter extends ModelFilter
{
    public $relations = [];

    public function name($name)
    {
        return $this->where('name', 'LIKE', "%".$name."%");;
    }

    public function range($range)
    {
        return $this->where(function($q) use ($range)
        {
            return $q->whereBetween('price', [$range->min, $range->max]);
        });
    }

    public function sku($sku)
    {
        return $this->where(function($q) use ($sku)
        {
            return $q->where('sku', $sku);
        });
    }

    public function isActive($value = true)
    {
        return $this->where('isActive', $value);
    }
}
