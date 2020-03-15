<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DistributorStoreTest extends TestCase
{
//    use DatabaseTransactions;

    private $store;
    public $data;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->store = new \App\Store\DistributorStore();

        parent::__construct($name, $data, $dataName);
    }

    public function testDistributorStoreSave()
    {

        $distributor = factory(\App\Models\Distributor::class)->make();

        $creditAccount = factory(\App\Models\CreditAccount::class)->make();


        $result = $this->store->save($distributor, $creditAccount);

        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        $this->assertTrue(is_object($result) && get_class($result) == \App\Models\Distributor::class);
    }

    public function testDistributorStoreUpdate()
    {
        $distributor = factory(\App\Models\Distributor::class)->make();

        $creditAccount = factory(\App\Models\CreditAccount::class)->make();

        $result = $this->store->save($distributor, $creditAccount);


        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        usleep(100 * 1000);

        $result1 = $this->store->update($result, $creditAccount);
        if (is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't update item", $result1);
        }

        $result2 = $result1;

        $result2->id = "missing";

        $result3 = $this->store->update($result2, $creditAccount);

        if (is_object($result3) && get_class($result3) != \App\Models\Error::class) {
            $this->failnow("Update should have failed because of missing key");
        }

        $result3->id = uniqid();

        $result4 = $this->store->update($result, $creditAccount);

        if (is_object($result4) && get_class($result4) != \App\Models\Error::class) {
            $this->failnow("Update should have failed because id not found");
        }

        $this->assertTrue(is_object($result1) && get_class($result1) == \App\Models\Distributor::class);
    }

    public function testDistributorStoreSearchByName()
    {
        $distributor = factory(\App\Models\Distributor::class)->make();

        $creditAccount = factory(\App\Models\CreditAccount::class)->make();


        $result = $this->store->save($distributor, $creditAccount);

        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        $result1 = $this->store->searchByName($distributor->name);

        if (is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't search by name", $result1);
        }

        if (is_object($result1) && get_class($result1) != \Illuminate\Database\Eloquent\Collection::class) {
            $this->failnow('Invalid returned distributor');
        }

        $this->assertTrue(is_null($result1) || (is_object($result1) && get_class($result1) == \Illuminate\Database\Eloquent\Collection::class));
    }

    public function testDistributorStoreGetByName()
    {
        $distributor = factory(\App\Models\Distributor::class)->make();

        $creditAccount = factory(\App\Models\CreditAccount::class)->make();

        $result = $this->store->save($distributor, $creditAccount);

        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        $result1 = $this->store->getByName($distributor->name);

        if (is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't get by name", $result1);
        }

        if (is_object($result1) && get_class($result1) != \App\Models\Distributor::class) {
            $this->failnow('Invalid returned distributor');
        }

        $this->assertTrue(is_null($result1) || (is_object($result1) && get_class($result1) == \App\Models\Distributor::class));
    }
}
