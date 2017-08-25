<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class DepositModel extends Model
{
    protected $table      = 'deposit';
    protected $primaryKey = 'deposit_id';
    protected $guarded    = [];
}
