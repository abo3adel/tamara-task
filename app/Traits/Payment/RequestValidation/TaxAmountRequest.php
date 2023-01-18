<?php

namespace App\Traits\Payment\RequestValidation;

trait TaxAmountRequest
{
    const TAX_AMOUNT_RULES = [
        'tax_amount' => 'required|array',
        'tax_amount.amount' => 'required|numeric',
        'tax_amount.currency' => 'required|string|size:3',
    ];
}
