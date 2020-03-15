<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BrandStoreTest extends TestCase
{
//    use DatabaseTransactions;

    private $brand;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->brand = new \App\Store\BrandStore();

        parent::__construct($name, $data, $dataName);
    }


    public function testBrandStoreSave()
    {
        $brand = factory(\App\Models\Brand::class)->make();

        $result = $this->brand->save($brand);

        if(is_object($result) && get_class($result) == \App\Models\Error::class)
        {
            $this->failnow("couldn't save item", $result);
        }

        $result1 = $this->brand->save($result);

        if (!is_object($result1) || (is_object($result1) && get_class($result1) != \App\Models\Error::class)) {
            $this->failnow("shouldn't be able to update from save");
        }

        $this->assertTrue(is_object($result) && get_class($result) == \App\Models\Brand::class);
    }

    public function testBrandStoreUpdate()
    {
        $brand = factory(\App\Models\Brand::class)->make();

        $result = $this->brand->save($brand);

        if (is_object($result) && get_class($result) == \App\Models\Error::class)
        {
            $this->failnow("couldn't save item", $result);
        }

        usleep(100 * 1000);

        $result1 = $this->brand->update($result);


        if (is_object($result1) && get_class($result1) == \App\Models\Error::class)
        {
            $this->failnow("couldn't update item", $result1);
        }

        $result2 = $result1;

        $result2->id = "missing";

        $result3 = $this->brand->update($result2);

        if (is_object($result3) && get_class($result3) != \App\Models\Error::class) {
            $this->failnow("Update should have failed because of missing key");
        }

        $result3->id = uniqid();

        $result4 = $this->brand->update($result);

        if (is_object($result4) && get_class($result4) != \App\Models\Error::class)
        {
            $this->failnow("Update should have failed because id not found");
        }

        $this->assertTrue(is_object($result1) && get_class($result1) == \App\Models\Brand::class);
    }


    //sreach by name
    public function testBrandStoreSearchByName() {
        $brand = factory(\App\Models\Brand::class)->make();

        $result = $this->brand->save($brand);

        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        $result1 = $this->brand->searchByName($brand->name);

        if (is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't search by name brand", $result1);
        }

        if (is_object($result1) && get_class($result1) != \Illuminate\Database\Eloquent\Collection::class) {
            $this->failnow('Invalid returned brand');
        }

        $this->assertTrue(is_null($result1) || (is_object($result1) && get_class($result1) == \Illuminate\Database\Eloquent\Collection::class));
    }
    //get by name brand
    public function  testBrandStoreGetByName() {
        $brand = factory(\App\Models\Brand::class)->make();

        $result = $this->brand->save($brand);

        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        $result1 = $this->brand->getByName($brand->name);

        if (is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        if(is_object($result1) && get_class($result1) != \App\Models\Brand::class) {
            $this->failnow('Invalid returned brand');
        }

        $this->assertTrue(is_null($result1) || (is_object($result1) && get_class($result1) == \App\Models\Brand::class));
    }
}