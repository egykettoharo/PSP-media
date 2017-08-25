<?php

namespace App\Services;

use App\Http\Models\CustomerModel;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Validator;
use App\Http\Models\DepositModel;
use App\Http\Models\WithdrawModel;
use DB;

class WithdrawService
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function response500()
    {
        return response([
            'errorCode'         => 500,
            'errorText'         => 'Error during processing the request.',
            'errorMessageLabel' => 'Error during processing the request.'
        ], 500);
    }

    public function add($data)
    {
        $rules = [
            'customer_id'   => 'numeric|required',
            'amount'        => 'numeric|min:1|required'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {

            $messages = $validator->messages();
            return response($messages, 422);

        } else {

            $real_amount = DepositModel::where('customer_id', $data['customer_id'])->sum('real_amount');

            if ($data['amount'] > $real_amount) {
                return response([
                    'amount_over' => 'Can\'t withdraw more than you have on balance!'
                ], 422);
            } else {

                DB::beginTransaction();

                try {

                    $withdraw = new WithdrawModel;
                    $withdraw->amount = $data['amount'];
                    $withdraw->customer_id = $data['customer_id'];
                    $withdraw->save();

                    DB::commit();

                } catch (\Exception $e) {
                    DB::rollback();

                    return self::response500();

                }

                return response('', 204);
            }
        }
    }
}