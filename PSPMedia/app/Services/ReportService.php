<?php

namespace App\Services;

use App\Http\Models\CustomerModel;
use App\Http\Models\DepositModel;
use App\Http\Models\WithdrawModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Validator;
use DB;

class ReportService
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

    public function makeReport($data)
    {
        $report     = [];

        $from       = strtotime($data['from']);
        $to         = strtotime($data['to']);
        $datediff   = $to - $from;

        $days = floor($datediff / (60 * 60 * 24));

        $countries = CustomerModel::groupBy('country')->pluck('country')->toArray();

        for ($i = 0; $i <= $days; $i++) {
            $date = Carbon::createFromFormat('Y-m-d', $data['from'])->addDays($i)->toDateString();

            foreach ($countries as $country) {

                $customer_ids = CustomerModel::where('country', $country)
                    ->pluck('customer_id')->toArray();

                $deposited_customer_ids = DepositModel::whereIn('customer_id', $customer_ids)
                    ->where('created_at', 'like', $date . '%')
                    ->pluck('customer_id')->toArray();

                $withdrawals_customer_ids = WithdrawModel::whereIn('customer_id', $customer_ids)
                    ->where('created_at', 'like', $date . '%')
                    ->pluck('customer_id')->toArray();

                $accepted_customer_ids = array_unique(array_merge($withdrawals_customer_ids, $deposited_customer_ids));

                $unique_customers = count($accepted_customer_ids);

                $deposit_count = DepositModel::whereIn('customer_id', $accepted_customer_ids)
                    ->where('created_at', 'like', $date . '%')
                    ->count('deposit_id');

                $deposit_amount = DepositModel::whereIn('customer_id', $accepted_customer_ids)
                    ->where('created_at', 'like', $date . '%')
                    ->sum('real_amount');

                $withdraw_count     = WithdrawModel::whereIn('customer_id', $accepted_customer_ids)
                    ->where('created_at', 'like', $date . '%')
                    ->count('withdraw_id');

                $withdraw_amount    = WithdrawModel::whereIn('customer_id', $accepted_customer_ids)
                    ->where('created_at', 'like', $date . '%')
                    ->sum('amount');

                if ( $deposit_count > 0 && $withdraw_amount > 0 ) {
                    $report[] = [
                        'date' => $date,
                        'country' => $country,
                        'no_of_unique_customers' => $unique_customers,
                        'no_of_deposits' => $deposit_count,
                        'total_deposit_amount' => $deposit_amount,
                        'no_of_withdrawals' => $withdraw_count,
                        'total_withdrawal_amount' => $withdraw_amount
                    ];
                }
            }
        }

        return $report;
    }
}