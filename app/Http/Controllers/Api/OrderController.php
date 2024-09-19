<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Services\PaymentGateway;

class OrderController extends Controller
{

    public function create(StoreRequest $request)
    {

        $data = $request->validated();
        $order = Order::create($data);
        foreach ($request->products as $product) {
            $order->products()->attach($product);
        }
        return response()->json(['order' => $order], 201);
    }

    public function pay(Order $order, PaymentGateway $paymentGateway, StoreRequest $request)
    {
        $validatedData = $request->validated();
        $orderId = $order->id;

        $amount = $order->amount;
        $result = $paymentGateway->payment($request, $orderId, $amount);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }


        $order->update(['status' => 'created']);

        return response()->json(['success' => $result], 200);
    }

    public function paymentCallback(StoreRequest $request, PaymentGateway $paymentGateway)

    {

        $json = $request->json()->all();

        $paymentGateway->handleCallback($json);

        return response()->json(['message' => 'success'], 200);
    }


}
