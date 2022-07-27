<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    public function formatSelect()
    {
        return [
            'value'=>$this->id,
            'label'=>$this->supplier_name. ' ( Mob: '. $this->supplier_phone.')'
        ];
    }
}
