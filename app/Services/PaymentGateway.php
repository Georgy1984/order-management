<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;



class PaymentGateway
{

    const URL = 'https://api.github.com/users/mralexgray/repos';
    public function pay(int $orderId) {

        $payresult = Http::withoutVerifying()->get(self::URL,['orderid' => $orderId]);

//        dd($payresult->json());
    }



}
