<?php

namespace Tests\Elements;

use \Shoprunback\Elements\Order;
use \Shoprunback\Elements\Customer;
use \Shoprunback\Elements\Address;

trait OrderTrait
{
    public static function getElementClass()
    {
        return 'Shoprunback\Elements\Order';
    }

    public static function createDefault()
    {
        $address = new Address();
        $address->line1 = static::randomString();
        $address->country_code = 'FR';
        $address->city = static::randomString();

        $customer = new Customer();
        $customer->first_name = static::randomString();
        $customer->last_name = static::randomString();
        $customer->email = 'test@test.com';
        $customer->phone = '0123456789';
        $customer->address = $address;

        $order = new Order();
        $order->order_number = static::randomString();
        $order->ordered_at = date('Y-m-d');
        $order->customer = $customer;

        return $order;
    }

    protected function checkIfHasNeededValues($order)
    {
        $this->assertInstanceOf(
            self::getElementClass(),
            $order
        );

        $this->assertNotNull($order->id);
        $this->assertNotNull($order->order_number);
        $this->assertNotNull($order->ordered_at);

        $this->assertInstanceOf(
            'Shoprunback\Elements\Customer',
            $order->customer
        );
        $this->assertNotNull($order->customer->first_name);
        $this->assertNotNull($order->customer->last_name);
        $this->assertNotNull($order->customer->email);
        $this->assertNotNull($order->customer->phone);

        $this->assertInstanceOf(
            'Shoprunback\Elements\Address',
            $order->customer->address
        );
        $this->assertNotNull($order->customer->address->line1);
        $this->assertNotNull($order->customer->address->country_code);
        $this->assertNotNull($order->customer->address->city);
    }

    public function testCannotUpdate()
    {
        $this->assertFalse(static::getElementClass()::canUpdate());
    }

    public function testCannotDelete()
    {
        $this->assertFalse(static::getElementClass()::canDelete());
    }

    public function testPrintOrderBody()
    {
        $orderNumber = static::randomString();
        $orderedAt = date('Y-m-d');

        $order = new Order();
        $order->order_number = $orderNumber;
        $order->ordered_at = $orderedAt;

        $orderNumber = json_encode($orderNumber);
        $orderedAt = json_encode($orderedAt);

        $this->expectOutputString($order . ': {"order_number":' . $orderNumber . ',"ordered_at":' . $orderedAt . '}' . "\n");
        $order->printElementBody();
    }
}