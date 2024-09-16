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
        foreach (array_unique($request->products) as $product) {
            $order->products()->attach($product);
        }
        return response()->json(['order' => $order], 201);
    }

    public function pay(Order $order, PaymentGateway $paymentGateway, StoreRequest $request)
    {
        $validatedData = $request->validated();

        $orderId = $validatedData['order_id'];
        $order = Order::findOrFail($orderId);

        $amount = $order->amount;

        $gateway = new PaymentGateway();
        $result = $gateway->payment($request, $orderId, $amount);

        if (isset($result['error'])) {
            return response()->json(['error' => $result['error']], 400);
        }

        return response()->json(['success' => $result], 200);


    }

    public function paymentCallback(Request $request)

    {

        $json = $request->json()->all();

        if ($json['status'] === 'paid') {
            Order::find($json['id'])->update(['status' => 'paid']);

        } else {

            Order::find($json['id'])->update(['status' => 'failed']);

        }

        return response()->json(['message' => 'Callback processed'], 200);
    }


}
