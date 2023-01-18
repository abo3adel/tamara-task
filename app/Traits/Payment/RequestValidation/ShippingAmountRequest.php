<?php

namespace App\Traits\Payment\RequestValidation;

trait ShippingAmountRequest
{
    const SHIPPING_AMOUNT_RULES = [
        'shipping_amount' => 'required|array',
        'shipping_amount.amount' => 'required|numeric',
        'shipping_amount.currency' => 'required|string|size:3',
    ];
}
