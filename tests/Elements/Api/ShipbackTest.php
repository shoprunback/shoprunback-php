<?php

namespace Tests\Elements\Api;

use \Tests\Elements\Api\BaseApiTest;

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
}