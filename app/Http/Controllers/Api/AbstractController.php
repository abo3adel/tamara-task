<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AbstractController extends Controller
{
    protected function respond($data = [], string $message = '', int $status = 200)
    {
        return response()->json(compact('data', 'message', 'status'));
    }
}
