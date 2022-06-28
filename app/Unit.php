<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public function formatSelect()
    {
        return [
            'value' => $this->id,
            'label' => $this->unit_name
        ];
    }
}
