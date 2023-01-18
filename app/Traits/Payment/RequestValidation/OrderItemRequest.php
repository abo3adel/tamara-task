<?php

namespace App\Traits\Payment\RequestValidation;

trait OrderItemRequest
{
    const ORDER_ITEM_RULES = [
        'items' => 'required|array',
        'items.*.reference_id' => 'required|string',
        'items.*.type' => 'required|string',
        'items.*.name' => 'required|string|max:255',
        'items.*.sku' => 'required|string|max:128',
        'items.*.image_url' => 'required|string|url',
        'items.*.quantity' => 'required|numeric|min:1',

        'items.*.unit_price' => 'required|array',
        'items.*.unit_price.*.amount' => 'required|numeric',
        'items.*.unit_price.*.currency' => 'required|string|max:3',

        'items.*.discount_amount' => 'required|array',
        'items.*.discount_amount.*.amount' => 'required|numeric',
        'items.*.discount_amount.*.currency' => 'required|string|max:3',

        'items.*.tax_amount' => 'required|array',
        'items.*.tax_amount.*.amount' => 'required|numeric',
        'items.*.tax_amount.*.currency' => 'required|string|max:3',

        'items.*.total_amount' => 'required|array',
        'items.*.total_amount.*.amount' => 'required|numeric',
        'items.*.total_amount.*.currency' => 'required|string|max:3',
    ];
}
