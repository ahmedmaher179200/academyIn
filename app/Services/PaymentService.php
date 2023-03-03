<?php

namespace App\Services;

use App\Http\Controllers\Controller;
use App\Traits\response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class PaymentService extends Controller
{
    use response;
    public function payment_request($amount, $callback, $return){
        $response = Http::withHeaders([
            'authorization' => config('app.paytabs_key'),
            'Content-Type'  => 'application/json'
        ])->post('https://secure-egypt.paytabs.com/payment/request', [
            "profile_id"=>         config('app.paytabs_profile_id'),
            "tran_type"=>          "sale",
            "tran_class"=>         "ecom",
            "cart_description"=>   "AS SDAs",
            "cart_id"=>            time() . rand(1,10000),
            "cart_currency"=>      "EGP",
            "cart_amount"=>        $amount,
            "hide_shipping"=>      true,
            "callback"=>           $callback,
            "return"=>             $return,
        ]);

        return $response;
    }

    public function check($tran_ref){
        $response = Http::withHeaders([
            'authorization' => config('app.paytabs_key'),
            'Content-Type'  => 'application/json'
        ])->post('https://secure-egypt.paytabs.com/payment/query', [
            "profile_id"    => config('app.paytabs_profile_id'),
            "tran_ref"      => $tran_ref
        ]);
        
        return $response;
    }
}