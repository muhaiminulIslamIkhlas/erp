<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function formatSelect()
    {
        return [
            'value'=>$this->id,
            'label'=>$this->account_name.' ( Balance: '.$this->current_balance.' )'
        ];
    }
}
