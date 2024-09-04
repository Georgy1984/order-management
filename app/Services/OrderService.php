<?php

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public static function createOrder($data): Order{
        return Order::create($data);

        //вернуть в работу сервис после тестирования.

    }



}
