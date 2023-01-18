<?php

return [
    'token' => env('TAMARA_API_TOKEN'),
    'url' => env('TAMARA_API_URL', 'https://api.tamara.co'),
    'timeout' => env('TAMARA_API_TIMEOUT', 60),
    'transport' => env('TAMARA_TRANSPORT'),
];