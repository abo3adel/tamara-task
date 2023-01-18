<?php

namespace App\Traits\Payment\RequestValidation;

trait DiscountRequest
{
    const DISCOUNT_RULES = [
        'discount' => 'nullable|array',
        'discount.name' => 'required|string|max:128',
        'discount.amount' => 'required|array',
        'discount.amount.amount' => 'required|numeric',
        'discount.amount.currency' => 'required|string|size:3',
    ];
}
