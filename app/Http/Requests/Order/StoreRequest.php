<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array',
            'products.*' => 'integer|exists:products,id|distinct',
            'price' => 'required|numeric|min:0',
            'order_id' => 'required|integer|exists:orders,id',
        ];
    }


}
