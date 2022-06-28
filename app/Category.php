<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function formatSelect()
    {
        return [
            'value' => $this->id,
            'label' => $this->category_name
        ];
    }
}
