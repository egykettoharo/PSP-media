<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawModel extends Model
{
    protected $table      = 'withdraw';
    protected $primaryKey = 'withdraw_id';
    protected $guarded    = [];
}
