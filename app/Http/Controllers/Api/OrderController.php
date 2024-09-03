<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function create(Request $request) {

//        $data = $request->validated(); Разобраться почему метод validated() -> не работает, для валидации реквеста.

        $order = Order::create(request()->all());
        foreach ($request->products as $product) {
            $order->products()->attach($product);
        }
        return response()->json(['order' => $order], 201);
    }

    public function pay(Order $order)
    {
        //подумать как реализовать отправку запроса на внешний сервис.
        //Реализовать изменение статуса на "оплачен". Продумать как сделать таймер на 10 минут, что бы изменить статус на "отменен".
        //'status' => 'created'
        return response()->json(['message' => 'Payment initiated'], 202);

    }

    public function paymentCallback(StoreRequest $request)
    {
        //если успешно - меняем статус "исполнен". Если не прошла оплата - меняем статус на "отменен".
        return response()->json(['message' => 'Callback processed'], 200);
    }





}
