<?php

namespace App\Http\Requests\Api\Payment;

use App\Traits\Payment\RequestValidation\BillingAddressRequest;
use App\Traits\Payment\RequestValidation\ConsumerRequest;
use App\Traits\Payment\RequestValidation\OrderItemRequest;
use App\Traits\Payment\RequestValidation\DiscountRequest;
use App\Traits\Payment\RequestValidation\ShippingAddressRequest;
use App\Traits\Payment\RequestValidation\ShippingAmountRequest;
use App\Traits\Payment\RequestValidation\TaxAmountRequest;
use Illuminate\Foundation\Http\FormRequest;
use Tamara\Model\Order\Consumer;
use Tamara\Model\Order\Discount;

class CheckoutRequest extends FormRequest
{
    use OrderItemRequest, ConsumerRequest, BillingAddressRequest, ShippingAddressRequest, DiscountRequest, TaxAmountRequest, ShippingAmountRequest;

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
            'order_reference_id' => 'required|string|min:6',
            'order_number' => 'nullable|string',
            'total_amount' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'description' => 'required|max:255',
            'country_code' => 'required|size:2',
            'payment_type' => 'required|string',
            'instalments' => 'required_if:payment_type',
            'locale' => 'nullable|string',

            // items
            ...self::ORDER_ITEM_RULES,

            // consumer
            ...self::CONSUMER_RULES,

            // billing address
            ...self::BILLING_ADDRESS_RULES,

            // shipping address
            ...self::SHIPPING_ADDRESS_RULES,

            // discount
            ...self::DISCOUNT_RULES,

            // tax_amount
            ...self::TAX_AMOUNT_RULES,

            // shipping_amount
            ...self::SHIPPING_AMOUNT_RULES
        ];
    }
}
