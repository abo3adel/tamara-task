<?php

namespace App\Http\Requests\Api;

use App\Traits\Payment\RequestValidation\DiscountRequest;
use App\Traits\Payment\RequestValidation\OrderItemRequest;
use App\Traits\Payment\RequestValidation\ShippingAmountRequest;
use App\Traits\Payment\RequestValidation\TaxAmountRequest;
use Illuminate\Foundation\Http\FormRequest;

class OrderRefundRequest extends FormRequest
{
    use OrderItemRequest, ShippingAmountRequest, TaxAmountRequest, DiscountRequest;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'capture_id' => 'required|string',

            'total_amount' => 'required|array',
            'total_amount.amount' => 'required|numeric',
            'total_amount.currency' => 'required|string',


            ...self::ORDER_ITEM_RULES,
            ...self::TAX_AMOUNT_RULES,
            ...self::SHIPPING_AMOUNT_RULES,
            ...self::DISCOUNT_RULES
        ];
    }
}
