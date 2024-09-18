<?php

namespace App\Services;

use App\Http\Requests\Order\StoreRequest;
use App\Mail\OrderExecutedMail;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;


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

            // Если конечно процессинговый сервак вернёт мне в ответе: 'status' и 'transaction_id'
            $responseBody = json_decode($response->getBody()->getContents(), true);

            return [
                'transaction_id' => $responseBody['transaction_id'],
                'status' => $responseBody['status']
            ];
        } catch (\Exception $e) {
            // Обработка исключений
            return ['error' => $e->getMessage()];
        }
    }

    protected function completeOrder($order)
    {

        $order->update(['status' => 'executed']);

        $this->sendOrderNotification($order);
    }

    public function handleCallback($json)
    {
        $order = Order::find($json['id']);

        if (is_null($order) || in_array($order->status, ['completed', 'failed'])) {
            return;                                  // Нельзя изменять финальные статусы
        }

        if ($json['status'] === 'paid') {
            $order->update(['status' => 'paid']);
            $this->completeOrder($order);            // Исполнение заказа
        } else {
            $order->update(['status' => 'failed']);
        }


    }

    protected function sendOrderNotification($order)
    {
        $details = [
            'order_id' => $order->id,
            'name' => $order->products,
            'completed_at' => now()
        ];

        Mail::to('jojo26rus@gmail.com')->send(new OrderExecutedMail($details));
    }


}
