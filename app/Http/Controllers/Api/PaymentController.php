<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderRefundRequest;
use App\Http\Requests\Api\Payment\CheckoutRequest;
use App\Http\Requests\Api\Payment\GetTypeRequest;
use App\Models\Order as ModelsOrder;
use App\Models\PaymentTransaction;
use App\Traits\Payment\CheckoutOrder;
use DateTime;
use Illuminate\Http\Request;
use Tamara\Client;
use Tamara\Configuration;
use Tamara\Model\Money;
use Tamara\Model\Order\Address;
use Tamara\Model\Order\Consumer;
use Tamara\Model\Order\Discount;
use Tamara\Model\Order\MerchantUrl;
use Tamara\Model\Order\Order;
use Tamara\Model\Order\OrderItem;
use Tamara\Model\Order\OrderItemCollection;
use Tamara\Model\Payment\Capture;
use Tamara\Model\ShippingInfo;
use Tamara\Request\Checkout\CreateCheckoutRequest;
use Tamara\Request\Order\GetOrderRequest;
use Tamara\Request\Payment\CaptureRequest;
use Tamara\Request\Payment\RefundRequest;

class PaymentController extends AbstractController
{
    use CheckoutOrder;

    private Client $client;

    public function __construct()
    {
        $configuration = Configuration::create(config('tamara.url'), config('tamara.token'), config('tamara.timeout'));

        $this->client = Client::create($configuration);
    }

    public function getTypes(GetTypeRequest $request)
    {
        $response = $this->client->getPaymentTypes(
            $request->country,
            $request->currency,
        );

        if ($response->isSuccess()) {
            return $this->respond($response->getPaymentTypes());
        }
    }

    public function checkout(CheckoutRequest $request)
    {       
        $response = $this->client->createCheckout(new CreateCheckoutRequest($this->getCheckoutOrder($request)));
        
        if ($response->isSuccess()) {
            $res = $response->getCheckoutResponse();
            
            // save this payment unique idgenerated from tamara
            $payment = PaymentTransaction::create([
                'tamara_order_id' => $res->getOrderId(),
                'checkout_url' => $res->getCheckoutUrl(),
                'checkout_id' => $res->getCheckoutId(),
            ]);

            if ($payment) {
                return $this->respond([
                    'checkout_url' => $res->getCheckoutUrl()
                ]);
            } else {
                return $this->respond([], 'an error occurred', 400);
            }
        }
    }

    public function capture(int $order)
    {
        $order = ModelsOrder::findOrFail($order);

        $response = $this->client->capture(new CaptureRequest(new Capture(
            $order->payment->tamara_order_id,
            new Money($order->total_amount, $order->currency),
            new Money($order->shipping_amount->amount, $order->shipping_amount->currency),
            new Money($order->tax_amount->amount, $order->tax_amount->currency),
            new Money($order->discount->amount, $order->discount->currency),
            new OrderItemCollection(),
            new ShippingInfo(new \DateTimeImmutable('now'), '')
        )));

        if ($response->isSuccess()) {
            PaymentTransaction::where('tamara_order_id', $response->getOrderId())->update([
                'capture_id' => $response->getCaptureId(),
            ]);

            return $this->respond($order, 'order captured successfully', 200);
        }

        return $this->respond(null, 'an error occurred', 400);
    }

    public function refund(OrderRefundRequest $request,int $order)
    {
        $order = ModelsOrder::findOrFail($order);

        $response = $this->client->refund(
            new RefundRequest($order->payment->tamara_order_id, $request->all())
        );

        if ($response->isSuccess()) {
            
            // TODO inser refunds into another table

            return $this->respond($response->getRefunds(), 'order captured successfully', 200);
        }

        return $this->respond(null, 'an error occurred', 400);
    }

    public function getOrderByReferenceId(Request $request, int $order)
    {
        $order = ModelsOrder::findOrFail($order);

        $response = $this->client->getOrderByReferenceId($order->reference_id);

        if ($response->isSuccess()) {
            return $this->respond([
                'status' => $response->getStatus(),
                'amount' => $response->getTotalAmount()
            ], 'order recived successfully', 200);
        }

        return $this->respond(null, 'an error occurred', 400);
    }

    public function getOrderByTamaraId(Request $request, int $order)
    {
        $order = ModelsOrder::findOrFail($order);

        $response = $this->client->getOrder(new GetOrderRequest($order->tamara_order_id));

        if ($response->isSuccess()) {
            return $this->respond([
                'status' => $response->getStatus(),
                'amount' => $response->getTotalAmount()
            ], 'order recived successfully', 200);
        }

        return $this->respond(null, 'an error occurred', 400);
    }
}
