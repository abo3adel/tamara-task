<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Tamara\Client as TamaraClient;
use Tamara\Configuration;
use Tamara\Request\Order\AuthoriseOrderRequest;

class PaymentNotificationService extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $notification = \Tamara\Notification\NotificationService::create(config('tamara.token'));
        $message = $notification->processAuthoriseNotification();

        $payment = PaymentTransaction::whereTamaraOrderId($message->getOrderId())->first();

        if (!$payment) {
            return redirect()->route('payment.get-types');
        }

        $configuration = Configuration::create(config('tamara.url'), config('tamara.token'), config('tamara.timeout'));
        $client = TamaraClient::create($configuration);

        if ($message->getOrderStatus() === 'approved') {
            // check if the payment has been paid successfully
            $authrized = $client->authoriseOrder(new AuthoriseOrderRequest($message->getOrderId()));
            
            $payment->status = $authrized->getOrderStatus();
        } else {
            $payment->status = $message->getOrderStatus();
        }

        $payment->data = json_encode($message->getData());
        $payment->save();
    }
}
