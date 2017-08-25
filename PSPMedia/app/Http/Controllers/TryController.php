<?php

namespace App\Http\Controllers;

use App\Http\Models\CustomerModel;
use App\Http\Models\DepositModel;
use DB;

class TryController extends Controller
{

    public function api()
    {
        echo "<pre>";
        var_dump(
            DB::table('deposit')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('sum(real_amount) as total'))
                ->groupBy(DB::raw('DATE(created_at)') )
                ->get()
        );
        echo "</pre>";
//        $ch = curl_init();
////
//        var_dump($ch);
//        curl_setopt($ch, CURLOPT_URL,  '127.0.0.1/api/user');
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
//            'email' => 'qwewqe@asd.com'
//        ]) );
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $json = curl_exec($ch);
//        $info = curl_getinfo($ch);
//        curl_close($ch);
//        var_dump($json);

//        var_dump($info);
    }
}
