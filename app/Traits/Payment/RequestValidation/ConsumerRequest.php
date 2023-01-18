<?php

namespace App\Traits\Payment\RequestValidation;

trait ConsumerRequest
{
    const CONSUMER_RULES = [
        'consumer' => 'required|array',
        'consumer.first_name' => 'required|string|max:50',
        'consumer.last_name' => 'required|string|max:50',
        'consumer.phone_number' => 'required|string|max:32',
        'consumer.email' => 'required|email|max:128',
    ];
}
