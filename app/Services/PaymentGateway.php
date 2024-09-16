<?php

namespace App\Services;

use App\Http\Requests\Order\StoreRequest;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;


class PaymentGateway
{

    protected $client;
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('payment.api_url');
        $this->apiKey = config('payment.api_key');

        //создаю клиента для отправки запроса
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout' => config('payment.timeout'),
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function payment(StoreRequest $request, $orderId, $amount)
    {
        $validatedData = $request->validated();
        try {
            $response = $this->client->post('/payments', [
                'json' => [
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'callback_url' => config('payment.callback_url'),
                    'products' => $validatedData['products'],
                    'price' => $validatedData['price'],
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            // Обработка исключений
            return ['error' => $e->getMessage()];
        }
    }


}
