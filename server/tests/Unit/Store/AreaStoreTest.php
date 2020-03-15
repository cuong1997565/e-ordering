<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AreaStoreTest extends TestCase
{
//    use DatabaseTransactions;

    private $store;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->store = new \App\Store\AreaStore();

        parent::__construct($name, $data, $dataName);
    }

    public function testAreaStoreSave()
    {
        $area = factory(\App\Models\Area::class)->make();

        $result = $this->store->save($area);

        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        $result1 = $this->store->save($result);

        if (!is_object($result1) || (is_object($result1) && get_class($result1) != \App\Models\Error::class)) {
            $this->failnow("shouldn't be able to update from save");
        }

        $this->assertTrue(is_object($result) && get_class($result) == \App\Models\Area::class);
    }

    public function testAreaStoreUpdate()
    {
        $area = factory(\App\Models\Area::class)->make();

        $result = $this->store->save($area);

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

        $this->assertTrue(is_object($result1) && get_class($result1) == \App\Models\Area::class);
    }
}
