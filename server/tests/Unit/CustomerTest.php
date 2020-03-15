<?php

abstract class CustomerTest extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createCustomerFromModel()
    {
        $customer = factory(App\Models\Customer::class)->make();
        return require __DIR__.'/../bootstrap/app.php';
    }
}
