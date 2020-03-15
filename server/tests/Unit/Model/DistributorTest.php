<?php

use Faker\Factory as Faker;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DistributorTest extends TestCase
{
    use DatabaseTransactions;

    private $model;

    private $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->model = new \App\Models\Distributor();

        $this->faker = Faker::create();

        parent::__construct($name, $data, $dataName);
    }

    public function testCreateDistributor()
    {
        $distributor = factory(\App\Models\Distributor::class)->make();

        $creditAccount = factory(\App\Models\CreditAccount::class)->make();

        $result = $distributor->createDistributor($creditAccount);


        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("Should create a new distributor", $result);
        }

        $distributor1 = factory(\App\Models\Distributor::class)->make();

        $result1 = $distributor1->createDistributor($creditAccount);

        if (is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("Should create a new distributor", $result1);
        }

        $distributor->email = $distributor1->email;

        $result2 = $distributor->createDistributor($creditAccount);

        if (!is_object($result2) || (is_object($result2) && get_class($result2) != \App\Models\Error::class)) {
            $this->failnow("Should not create a new distributor - distributor already exists");
        }

        $this->assertTrue(is_object($result1) && get_class($result1) == \App\Models\Distributor::class);
    }

//    public function testUpdateDistributor()
//    {
//        $distributor = factory(\App\Models\Distributor::class)->make();
//
//        $result = $distributor->createDistributor();
//
//        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
//            $this->failnow("Should create a new distributor", $result);
//        }
//
//        usleep(100 * 1000);
//
//
//    }
}
