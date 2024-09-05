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

    public function create(Request $request) {

//        $data = $request->validated(); Разобраться почему метод validated() -> не работает, для валидации реквеста.

        $order = Order::create(request()->all());
        foreach (array_unique($request->products) as $product) {
            $order->products()->attach($product);
        }
        return response()->json(['order' => $order], 201);
    }

    public function pay(Order $order, PaymentGateway $paymentGateway)
    {
        $paymentGateway->pay($order->id);

        //подумать как реализовать отправку запроса на внешний сервис.
        //Реализовать изменение статуса на "оплачен". Продумать как сделать таймер на 10 минут, что бы изменить статус на "отменен".
        //'status' => 'created'
        return response()->json(['message' => 'Payment initiated'], 202);

        //нужно ли добавить обработку ответа от процессингового сервака? К примеру: банк упал.

    }

    public function paymentCallback(Request $request)
    {

        $json = $request->json()->all();
        //       $order =  Order::find($json['id']);
        if ($json['status'] === 'paid') {
            Order::find($json['id'])->update(['status' => 'paid']);

        } else {

            Order::find($json['id'])->update(['status' => 'failed']);

        }
        // $id = $json['id'] ?? null;
        //сделать валидацию на входящий json (фильтровать статусы колбека и проверить, что в теле есть ID). Подумать как достать из входящего json ID и статус закза другим способом.
        //получить по ID заказ из базы. Если заказ не найден, вернуть ответ с ошибкой.
        //проверить статус заказа. Если он исполнен или faild, то вернуть ошибку, что не верный статус заказа.
        //при обновлении статусов Paid, нужно прописать paid_at.
        //        dd($json);

        //если успешно - меняем статус "исполнен". Если не прошла оплата - меняем статус на "отменен".
        return response()->json(['message' => 'Callback processed'], 200);
    }





}
