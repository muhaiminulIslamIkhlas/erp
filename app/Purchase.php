<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function supplier(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Supplier', 'supplier_id');
    }

    public function details(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany('App\PurchaseDetail', 'purchase_id');
    }

    public function formatList()
    {
        return [
            'id' => $this->id,
            'invoice_no' => $this->invoice_no,
            'supplier' => $this->supplier->supplier_name,
            'grand_total' => $this->grand_total,
            'payment' => $this->payment,
            'due' => $this->due,
        ];
    }

    public function formatDetails()
    {
        $items = [];
        foreach ($this->details as $item) {
            $items[] = $item->format();
        }

        return [
            'id' => $this->id,
            'invoice_no' => $this->invoice_no,
            'supplier' => $this->supplier->supplier_name,
            'grand_total' => $this->grand_total,
            'payment' => $this->payment,
            'due' => $this->due,
            'date' => $this->date,
            'discount' => $this->discount,
            'other_cost' => $this->other_cost,
            'items' => $items,
        ];
    }
}
