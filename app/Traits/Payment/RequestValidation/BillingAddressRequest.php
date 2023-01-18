<?php

namespace App\Traits\Payment\RequestValidation;

trait BillingAddressRequest
{
    const BILLING_ADDRESS_RULES = [
        'billing_address' => 'required|array',
        'billing_address.first_name' => 'required|string|max:60',
        'billing_address.last_name' => 'required|string|max:60',
        'billing_address.line1' => 'required|string|max:128',
        'billing_address.line2' => 'nullable|string|max:128',
        'billing_address.region' => 'nullable|string|max:128',
        'billing_address.postal_code' => 'nullable|string|max:50',
        'billing_address.city' => 'required|string|max:128',
        'billing_address.country_code' => 'required|string|max:5',
        'billing_address.phone_number' => 'nullable|string|max:32',
    ];
}
