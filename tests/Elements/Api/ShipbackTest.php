<?php

namespace Tests\Elements\Api;

use \Tests\Elements\Api\BaseApiTest;
use \Tests\Elements\Api\OrderTest;

use \Shoprunback\Elements\Shipback;
use \Shoprunback\Elements\Order;
use \Shoprunback\Elements\Item;
use \Shoprunback\Elements\Product;
use \Shoprunback\RestClient;

final class ShipbackTest extends BaseApiTest
{
    use \Tests\Elements\ShipbackTrait;

    public function testCanSaveNewShipback()
    {
        RestClient::getClient()->disableTesting();

        $shipback = new Shipback();
        $shipback->order_id = Order::All()->getLast()->id;

        $shipback->save();

        $this->assertNotNull($shipback->id);
    }

    public function testCanUpdate()
    {
        RestClient::getClient()->disableTesting();

        $shipback = Shipback::all()[0];
        $shipbackId = $shipback->id;
        $rma = self::randomRma();
        $shipback->rma = $rma;
        $shipback->save();

        $retrievedShipback = Shipback::retrieve($shipbackId);

        $this->assertSame($retrievedShipback->rma, $rma);
    }

    public function testObjectFromApiIsPersisted()
    {
        RestClient::getClient()->disableTesting();

        $shipback = new Shipback();
        $this->assertFalse($shipback->isPersisted());

        $order = OrderTest::createDefault();
        $shipback->order = $order;
        $shipback->save();
        $this->assertTrue($shipback->isPersisted());
    }
}