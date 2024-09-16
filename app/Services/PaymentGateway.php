<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;



class PaymentGateway
{

    public function pay(int $orderId) {
        $url = env('GITHUB_REPO_URL');

        $payresult = Http::withoutVerifying()->get($url ,['orderid' => $orderId]);

//        dd($payresult->json());
    }



}
