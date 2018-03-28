<?php

namespace Tests\Elements;

use \Shoprunback\Elements\Shipback;
use \Shoprunback\Elements\Order;
use \Shoprunback\Elements\Item;
use \Shoprunback\Elements\Product;
use \Shoprunback\Elements\Customer;
use \Shoprunback\Elements\Address;
use \Shoprunback\RestClient;

trait ShipbackTrait
{
    public static function getElementClass()
    {
        return 'Shoprunback\Elements\Shipback';
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

        $product;
        if (RestClient::getClient()->isTesting()) {
            $product = Product::Retrieve(1);
        } else {
            $product = Product::all()[0];
        }

        $item = new Item();
        $item->product = $product;
        $item->product_id = $product->id;
        $item->label = $product->label;
        $item->reference = $product->reference;
        $item->barcode = '9782700507089';
        $item->price_cents = 1000;
        $item->currency = 'eur';
        $item->created_at = '2017-06-15T16:17:46.482+02:00';

        $order = new Order();
        $order->order_number = static::randomString();
        $order->ordered_at = date('Y-m-d');
        $order->customer = $customer;
        $order->items = [$item, $item];

        $shipback = new Shipback();
        $shipback->rma = self::randomRma();
        $shipback->mode = self::randomMode();
        $shipback->weight_in_grams = 1000;
        $shipback->computed_weight_in_grams = 1020;
        $shipback->created_at = '2017-06-15T16:17:46.435+02:0';
        $shipback->public_url = 'http://localhost:3002/company/123';
        $shipback->returned_items = [$order->items[1]];
        $shipback->order_id = $order->id;
        $shipback->order = $order;
        $shipback->customer = $customer;

        return $shipback;
    }

    protected function checkIfHasNeededValues($shipback)
    {
        $this->assertInstanceOf(
            self::getElementClass(),
            $shipback
        );

        $this->assertNotNull($shipback->order_id);

        $this->assertInstanceOf(
            'Shoprunback\Elements\Order',
            $shipback->order
        );

        $this->assertNotNull($shipback->order->id);
        $this->assertEquals($shipback->order->id, $shipback->order_id);
        $this->assertNotNull($shipback->order->order_number);
        $this->assertNotNull($shipback->order->ordered_at);
        $this->assertNotNull($shipback->order->items);
        $this->assertNotNull($shipback->order->created_at);
        $this->assertEquals($shipback->order->shipback_id, $shipback->id);
    }

    public function testCannotUpdate()
    {
        $this->assertTrue(static::getElementClass()::canUpdate());
    }

    public function testCanDelete()
    {
        $this->assertTrue(static::getElementClass()::canDelete());
    }

    public function testPrintShipbackBody()
    {
        $rma = self::randomRma();
        $mode = self::randomMode();
        $weight_in_grams = 1000;
        $computed_weight_in_grams = 1020;
        $public_url = 'http://localhost:3002/company/123';

        $shipback = new Shipback();
        $shipback->rma = $rma;
        $shipback->mode = $mode;
        $shipback->weight_in_grams = $weight_in_grams;
        $shipback->computed_weight_in_grams = $computed_weight_in_grams;
        $shipback->public_url = $public_url;

        $rma = json_encode($rma);
        $mode = json_encode($mode);
        $weight_in_grams = json_encode($weight_in_grams);
        $computed_weight_in_grams = json_encode($computed_weight_in_grams);
        $public_url = json_encode($public_url);

        $this->expectOutputString($shipback . ': {"rma":' . $rma . ',"mode":' . $mode . ',"weight_in_grams":' . $weight_in_grams . ',"computed_weight_in_grams":' . $computed_weight_in_grams . ',"public_url":' . $public_url . '}' . "\n");
        $shipback->printElementBody();
    }

    public static function randomRma()
    {
        $seed = str_split('abcdefghijklmnopqrstuvwxyz'
                 .'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                 .'0123456789');
        $randomCharacters = array_rand($seed, 12);
        $rma = '';
        foreach ($randomCharacters as $key => $character) {
            if ($key % 3 == 0 && $key > 0) {
                $rma .= ':';
            }

            $rma .= $character;
        }
        return $rma;
    }

    public static function randomMode()
    {
        return array_rand(['postal', 'pickup', 'dropoff', 'direct'], 1);
    }
}