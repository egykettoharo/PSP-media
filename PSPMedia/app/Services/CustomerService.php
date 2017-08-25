<?php

namespace App\Services;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Validator;
use App\Http\Models\CustomerModel;
use DB;

class CustomerService
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

    public function create($data)
    {
        $rules = [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'gender'        => 'required',
            'country'       => 'required',
            'email'         => 'unique:customer,email|required|email'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {

            $messages = $validator->messages();
            return response($messages, 422);

        } else {

            DB::beginTransaction();

            try {

                $customer               = new CustomerModel;
                $customer->first_name   = $data['first_name'];
                $customer->last_name    = $data['last_name'];
                $customer->gender       = $data['gender'];
                $customer->country      = $data['country'];
                $customer->email        = $data['email'];
                $customer->bonus        = rand(5, 20);
                $customer->save();

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();

                return self::response500();

            }

            return response('', 204);
        }
    }

    public function edit($data)
    {
        $rules = [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'gender'        => 'required',
            'country'       => 'required',
            'email'         => 'unique:customer,email|required|email'
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {

            $messages = $validator->messages();
            return response($messages, 422);

        } else {

            DB::beginTransaction();

            try {

                $customer               = CustomerModel::find($data['customer_id']);
                $customer->first_name   = $data['first_name'];
                $customer->last_name    = $data['last_name'];
                $customer->gender       = $data['gender'];
                $customer->country      = $data['country'];
                $customer->email        = $data['email'];
                $customer->bonus        = rand(5, 20);
                $customer->save();

                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();

                return self::response500();

            }

            return response('', 204);
        }
    }
}