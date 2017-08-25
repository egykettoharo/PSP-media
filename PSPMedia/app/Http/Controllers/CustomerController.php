<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CustomerService;
use App\Services\DepositService;
use App\Services\WithdrawService;
use App\Services\ReportService;

class CustomerController extends Controller
{

    public function create(Request $request)
    {
        $customer_service = new CustomerService();

        return $customer_service->create($request->all());
    }

    public function edit(Request $request)
    {
        $customer_service = new CustomerService();

        return $customer_service->edit($request->all());
    }

    public function addDeposit(Request $request)
    {
        $deposit_service = new DepositService();

        return $deposit_service->add($request->all());
    }

    public function addWithdraw(Request $request)
    {
        $withdraw_service = new WithdrawService();

        return $withdraw_service->add($request->all());
    }

    public function report(Request $request)
    {
        $withdraw_service = new ReportService();

        return response($withdraw_service->makeReport($request->all()));
    }
}
