<?php

namespace App\Traits\Payment\RequestValidation;

trait ShippingAddressRequest
{
    const SHIPPING_ADDRESS_RULES = [
        'shipping_address' => 'required|array',
        'shipping_address.first_name' => 'required|string|max:60',
        'shipping_address.last_name' => 'required|string|max:60',
        'shipping_address.line1' => 'required|string|max:128',
        'shipping_address.line2' => 'nullable|string|max:128',
        'shipping_address.region' => 'nullable|string|max:128',
        'shipping_address.postal_code' => 'nullable|string|max:50',
        'shipping_address.city' => 'required|string|max:128',
        'shipping_address.country_code' => 'required|string|max:5',
        'shipping_address.phone_number' => 'nullable|string|max:32',
    ];
}
