<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    protected $table      = 'customer';
    protected $primaryKey = 'customer_id';
    protected $guarded    = [];


    public function deposits()
    {
        return $this->hasMany('App\Http\Models\DepositModel', 'customer_id', 'customer_id');
    }


    public function withdraws()
    {
        return $this->hasMany('App\Http\Models\WithdrawModel', 'customer_id', 'customer_id');
    }

    public function deposit_amount() {
        return $this->leftJoin('deposit', 'customer_id', 'customer_id')
            ->select('*', 'SUM(real_amount) as sum');
    }

}
