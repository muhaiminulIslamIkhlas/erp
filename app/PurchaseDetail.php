<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Product', 'product_id');
    }

    public function format()
    {
        return [
            'product'=>$this->product->product_name,
            'unit_price'=>$this->unit_price,
            'qty'=>$this->qty,
            'total'=>$this->total,
        ];
    }
}
