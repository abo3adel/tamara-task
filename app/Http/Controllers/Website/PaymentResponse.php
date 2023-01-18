<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentResponse extends Controller
{
    public function success()
    {
        return 'success';
    }

    public function failure()
    {
        return 'failure';
    }

    public function cancel()
    {
        return 'cancel';
    }
}
