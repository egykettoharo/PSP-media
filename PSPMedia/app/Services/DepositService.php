<?php

namespace App\Services;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Validator;
use App\Http\Models\DepositModel;
use DB;

class DepositService
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
            'real_amount'   => 'numeric|min:1|required'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {

            $messages = $validator->messages();
            return response($messages, 422);

        } else {

            DB::beginTransaction();

            try {

                $deposit               = new DepositModel;
                $deposit->real_amount  = $data['real_amount'];
                $deposit->customer_id  = $data['customer_id'];
                $deposit->bonus_amount = ($data['real_amount'] / 10);
                $deposit->save();

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();

                return self::response500();

            }

            return response('', 204);
        }
    }
}