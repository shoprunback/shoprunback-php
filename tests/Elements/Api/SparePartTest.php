<?php

namespace Tests\Elements\Api;

use \Tests\Elements\Api\BaseApiTest;

use \Shoprunback\Elements\SparePart;
use \Shoprunback\RestClient;
use \Shoprunback\Error\NotFoundError;

final class SparePartTest extends BaseApiTest
{
    use \Tests\Elements\SparePartTrait;

    public function testCanSaveNewSparePart()
    {
        static::disableTesting();
        $sparePart = self::createDefault(); 
        $sparePartBefore = $sparePart;
        $sparePart->save();
        $this->assertNotNull($sparePart->id);
        $this->assertSame($sparePart->name, $sparePartBefore->name);
        $this->assertSame($sparePart->reference, $sparePartBefore->reference);
    }

    public function testCanUpdate()
    {
        static::disableTesting();

        $sparePart = SparePart::retrieve(1);
        $sparePartId = $sparePart->id;
        $name = self::randomString();
        $sparePart->name = $name;
        $sparePart->picture_url = "http://1001startups.fr/wp-content/uploads/2016/05/SRB-Logo-primaire-couleurs.jpg";
        $sparePart->save();

        $retrievedSparePart = SparePart::retrieve($sparePartId);

        $this->assertSame($retrievedSparePart->name, $name);
    }
}
