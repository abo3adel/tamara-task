<?php

namespace App\Traits\Payment;

use App\Http\Requests\Api\Payment\CheckoutRequest;
use GuzzleHttp\Psr7\Request;
use Tamara\Model\Money;
use Tamara\Model\Order\Address;
use Tamara\Model\Order\Consumer;
use Tamara\Model\Order\Discount;
use Tamara\Model\Order\MerchantUrl;
use Tamara\Model\Order\Order;
use Tamara\Model\Order\OrderItem;
use Tamara\Model\Order\OrderItemCollection;

trait CheckoutOrder
{
    protected function getCheckoutOrder(CheckoutRequest $request): Order
    {
        $items = $this->getItems($request);

        $consumer = (new Consumer)->fromArray([
            'first_name' => $request->consumer->first_name,
            'last_name' => $request->consumer->last_name,
            'phone_number' => $request->consumer->phone_number,
            'email' => $request->consumer->email,
        ]);

        $billingAddress = (new Address)->fromArray([
            'first_name' => $request->billing_address->first_name,
            'last_name' => $request->billing_address->last_name,
            'line1' => $request->billing_address->line1,
            'line2' => $request->billing_address->line2,
            'region' => $request->billing_address->region,
            'city' => $request->billing_address->city,
            'country_code' => $request->billing_address->country_code,
            'phone_number' => $request->billing_address->phone_number,
        ]);

        $shippingAddress = (new Address)->fromArray([
            'first_name' => $request->shipping_address->first_name,
            'last_name' => $request->shipping_address->last_name,
            'line1' => $request->shipping_address->line1,
            'line2' => $request->shipping_address->line2,
            'region' => $request->shipping_address->region,
            'city' => $request->shipping_address->city,
            'country_code' => $request->shipping_address->country_code,
            'phone_number' => $request->shipping_address->phone_number,
        ]);

        $discount = new Discount($request->discount->name, new Money($request->discount->amount->amount, $request->discount->amount->currency));

        $taxAmount = new Money($request->tax_amount->amount, $request->tax_amount->currency);

        $shippingAmount = new Money($request->shipping_amount->amount, $request->tax_amount->currency);

        $merchentUrl = (new MerchantUrl)
            ->setSuccessUrl(route('payment.success'))
            ->setFailureUrl(route('payment.failure'))
            ->setCancelUrl(route('payment.cancel'))
            ->setNotificationUrl(route('payment.notification'));



        $order = new Order;
        return $order->setOrderReferenceId($request->order_refrence_id)
            ->setOrderNumber($request->order_number)
            ->setTotalAmount(new Money($request->amount, $request->currency))
            ->setDescription($request->description)
            ->setCountryCode($request->country_code)
            ->setPaymentType($request->payment_type)
            ->setInstalments($request->instalments)
            ->setLocale($request->locale)
            ->setItems($items)
            ->setConsumer($consumer)
            ->setBillingAddress($billingAddress)
            ->setShippingAddress($shippingAddress)
            ->setDiscount($discount)
            ->setTaxAmount($taxAmount)
            ->setShippingAmount($shippingAmount)
            ->setMerchantUrl($merchentUrl)
            ->setPlatform('mobile');
    }

    private function getItems(CheckoutRequest $request)
    {
        $items = new OrderItemCollection;
        foreach ($request->items as $item) {
            $items->append((new OrderItem)->fromArray(
                [
                    'reference_id' => $item->id,
                    'type' => $item->type,
                    'name' => $item->name,
                    'sku' => $item->sku,
                    'image_url' => $item->image_url,
                    'quantity' => $item->quantity,
                    'unit_price' => new Money($item->unit_price->amount, $item->unit_price->currency),
                    'discount_amount' => new Money($item->discount_amount->amount, $item->discount_amount->currency),
                    'tax_amount' => new Money($item->tax_amount->amount, $item->tax_amount->currency),
                    'total_amount' => new Money($item->total_amount->amount, $item->total_amount->currency),
                ]
            ));
        }

        return $items;
    }
}