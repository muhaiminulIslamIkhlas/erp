<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public function formatSelect()
    {
        return [
            'value'=>$this->id,
            'label'=>$this->brand_name
        ];
    }
}
