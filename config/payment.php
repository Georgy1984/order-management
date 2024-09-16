<?php

return [

      'api_url' => env('PAYMENT_API_URL', '  '),
      'api_key' => env('PAYMENT_API_KEY', '  '),
      'callback_url' => env('PAYMENT_CALLBACK_URL', '  '),
      'timeout' => env('PAYMENT_TIMEOUT', 30),

];
