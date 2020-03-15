<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ProductStoreTest extends TestCase
{
//    use DatabaseTransactions;

    private $product;
    private $areas;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->product = new \App\Store\ProductStore();
        $this->areas = new \App\Store\AreaStore();
        parent::__construct($name, $data, $dataName);
    }


    public function testProductStoreSave()
    {
        $faker = Faker\Factory::create();

        $product = factory(\App\Models\Product::class)->make();

        $featureitem = factory(\App\Models\FeatureItem::class, 3)->make();
        $featureitem = \App\Models\FeatureItem::all()->pluck('id')->toArray();
        $featureitem = count($featureitem) > 0 ? $faker->randomElement($featureitem, 2) : null;
        $specifications = null;

        $result = $this->product->save($product, $featureitem,null, null);

        if ( is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item ", $result);
        }

        $result1 = $this->product->save($result, $featureitem, null, null);

        if (!is_object($result1) || (is_object($result1) && get_class($result1) != \App\Models\Error::class))
        {
            $this->failnow("shouldn't be able to update from save");
        }

        $result2  = $result;

        $result2['id'] = null;

        $result2['product_type_id'] = "missing";

        $result2['uom_id'] = "missing";

        $result2['category_id'] = "missing";

        $result2['grade_group_id'] = "missing";

        $result3 = $this->product->save($result2, $featureitem, null, null);

        if(!is_object($result3) || (is_object($result3) && get_class($result3) != \App\Models\Error::class))
        {
            $this->failnow("Create should have failed because product id of missing key");
        }



        $this->assertTrue(is_object($result) && get_class($result) == \App\Models\Product::class);

    }

    public function testProductStoreUpdate() {
        $faker = Faker\Factory::create();

        $product = factory(\App\Models\Product::class)->make();

        $featureitem = factory(\App\Models\FeatureItem::class, 3)->make();
        $featureitem = \App\Models\FeatureItem::all()->pluck('id')->toArray();
        $featureitem = count($featureitem) > 0 ? $faker->randomElement($featureitem, 2) : null;

        $result = $this->product->save($product, $featureitem, null,null);

        if ( is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item product", $result);
        }

        usleep(100 * 1000);

        $result1 = $this->product->update($result, $featureitem, null,null);

        if(is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't update item product", $result);
        }

        $result2 = $result1;

        $result2->id = "missing";

        $result3 = $this->product->update($result2, $featureitem,null,null);

        if (is_object($result3) && get_class($result3) != \App\Models\Error::class) {
            $this->failnow("Update should have failed product because of missing key");
        }

        $result3->id = uniqid();

        $result4 = $this->product->update($result, $featureitem, null,null);

        if (is_object($result4) && get_class($result4) != \App\Models\Error::class) {
            $this->failnow("Update should have failed because id not found");
        }

        $result5 = $result1;

        $result5['product_type_id'] = "missing";

        $result6 = $this->product->save($result5, $featureitem,null,null);

        if (is_object($result6) && get_class($result6) != \App\Models\Error::class) {
            $this->failnow("Create should have failed because product type id id of missing key");
        }

        $result7 = $result1;

        $result7['uom_id'] = "missing";

        $result8 = $this->product->save($result7, $featureitem,null,null);

        if (is_object($result8) && get_class($result8) != \App\Models\Error::class) {
            $this->failnow("Create should have failed because uom id of missing key");
        }


        $result9 = $result1;

        $result9['category_id'] = "missing";

        $result10 = $this->product->save($result9, $featureitem,null,null);

        if (is_object($result8) && get_class($result8) != \App\Models\Error::class) {
            $this->failnow("Create should have failed because category id of missing key");
        }



        $this->assertTrue(is_object($result) && get_class($result) == \App\Models\Product::class);
    }
    //unit test sreach product
    public function testProductStoresGetProducts()
    {

        $product = factory(\App\Models\Product::class)->make();

        $result = $this->product->save($product, null, null,null);

        if (is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        $result1 = $this->product->getProducts(null, $product->category_id, $product->code);

        if (is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't search by brand and category", $result1);
        }

        if (is_object($result1) && get_class($result1) != \Illuminate\Pagination\LengthAwarePaginator::class) {
            $this->failnow('Invalid returned products');
        }

        $this->assertTrue(is_null($result1) || (is_object($result1) && get_class($result1) == \Illuminate\Pagination\LengthAwarePaginator::class));
    }
//
//    //get detail product
    public function testProductStoresGetDetail() {
        $product = factory(\App\Models\Product::class)->make();

        $result = $this->product->save($product, null, null,null);

        if(is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item", $result);
        }

        $result1 = $this->product->getDetail($result->id, 1);

        if(is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't by detail product", $result1);
        }

        if(is_object($result1) && get_class($result1) != \App\Models\Product::class) {
            $this->failnow('Invalid returned products');
        }

        $this->assertTrue(is_null($result1) || (is_object($result1) && get_class($result1) == \App\Models\Product::class));


    }
}