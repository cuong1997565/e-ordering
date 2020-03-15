<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class StoreTest extends TestCase
{
    private $store;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->store = new \App\Store\Stores();

        parent::__construct($name, $data, $dataName);
    }

    public function testStoreSave()
    {
        $store = factory(\App\Models\Store::class)->make();
        $result = $this->store->save($store);

        if (is_object($result) && get_class($result) == \App\Models\Error::class)
        {
            $this->failnow("couldn't save item ", $result);
        }

        $result1 = $this->store->save($result);

        if (!is_object($result1) || (is_object($result1) && get_class($result1) != \App\Models\Error::class))
        {
            $this->failnow("shouldn't be able to update from save");
        }

        $result2  = $result;

        $result2['id'] = null;

        $result2['factory_id'] = "missing";

        $result3 = $this->store->save($result2);

        if(!is_object($result3) || (is_object($result3) && get_class($result3) != \App\Models\Error::class))
        {
            $this->failnow("Create should have failed because factory id of missing key");
        }

        $this->assertTrue(is_object($result) && get_class($result) == \App\Models\Store::class);

    }

    public function testStoreUpdate()
    {
        $store = factory(\App\Models\Store::class)->make();

        $result = $this->store->save($store);

        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        usleep(100 * 1000);

        $result1 = $this->store->update($result);

        if (is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't update item", $result1);
        }

        $result2 = $result1;

        $result2->id = "missing";

        $result3 = $this->store->update($result2);

        if (is_object($result3) && get_class($result3) != \App\Models\Error::class) {
            $this->failnow("Update should have failed because of missing key");
        }

        $result3->id = uniqid();

        $result4 = $this->store->update($result);

        if (is_object($result4) && get_class($result4) != \App\Models\Error::class) {
            $this->failnow("Update should have failed because id not found");
        }

        $result5 = $result1;

        $result5['id'] = null;

        $result5['factory_id'] = "missing";

        $result6 = $this->store->save($result5);

        if(!is_object($result6) ||(is_object($result6) && get_class($result6) != \App\Models\Error::class))
        {
            $this->failnow("Create should have failed because factory id of missing key");
        }


        $this->assertTrue(is_object($result1) && get_class($result1) == \App\Models\Store::class);

    }


}
