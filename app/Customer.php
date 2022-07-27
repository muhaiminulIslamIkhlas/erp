<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function formatSelect()
    {
        return [
            'value'=>$this->id,
            'label'=>$this->customer_name. " ( Phone: ".$this->customer_phone ." )"
        ];
    }
}
