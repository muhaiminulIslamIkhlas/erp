<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function unit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Unit', 'unit_id');
    }

    public function brand(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Brand', 'brand_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function format()
    {
        return [
            'product_name' => $this->product_name,
            'selling_price' => $this->selling_price,
            'initial_stock' => $this->initial_stock,
            'brand' => $this->brand->brand_name,
            'category'=>$this->category->category_name,
        ];
    }
}
