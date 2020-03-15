<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CategoryStoreTest extends TestCase
{
//    use DatabaseTransactions;

    private $category;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->category = new \App\Store\CategoryStore();

        parent::__construct($name, $data, $dataName);
    }


    public function testCategoryStoreSave()
    {
        $category = factory(\App\Models\Category::class)->make();

        $result = $this->category->save($category);

        if(is_object($result) && get_class($result) == \App\Models\Error::class)
        {
            $this->failnow("couldn't save item", $result);
        }

        $result1 = $this->category->save($result);

        if (!is_object($result1) || (is_object($result1) && get_class($result1) != \App\Models\Error::class)) {
            $this->failnow("shouldn't be able to update from save");
        }

        $this->assertTrue(is_object($result) && get_class($result) == \App\Models\Category::class);
    }

    public function testCategoryStoreUpdate()
    {
        $category = factory(\App\Models\Category::class)->make();

        $result = $this->category->save($category);

        if (is_object($result) && get_class($result) == \App\Models\Error::class)
        {
            $this->failnow("couldn't save item", $result);
        }

        usleep(100 * 1000);

        $result1 = $this->category->update($result);


        if (is_object($result1) && get_class($result1) == \App\Models\Error::class)
        {
            $this->failnow("couldn't update item", $result1);
        }

        $result2 = $result1;

        $result2->id = "missing";

        $result3 = $this->category->update($result2);

        if (is_object($result3) && get_class($result3) != \App\Models\Error::class) {
            $this->failnow("Update should have failed because of missing key");
        }

        $result3->id = uniqid();

        $result4 = $this->category->update($result);

        if (is_object($result4) && get_class($result4) != \App\Models\Error::class)
        {
            $this->failnow("Update should have failed because id not found");
        }

        $this->assertTrue(is_object($result1) && get_class($result1) == \App\Models\Category::class);
    }

    //sreach by name category
    public function testCategoryStoreSearchByName()
    {
      $category = factory(\App\Models\Category::class)->make();

       $result = $this->category->save($category);

      if (is_object($result) && get_class($result) == \App\Models\Error::class) {
          $this->failnow("couldn't save item", $result);
      }

      $result1 = $this->category->searchByName($category->name);

      if (is_object($result1) && get_class($result1) == \App\Models\Error::class) {
          $this->failnow("couldn't search by name category", $result1);
      }

      if (is_object($result1) && get_class($result1) != \Illuminate\Database\Eloquent\Collection::class) {
          $this->failnow('Invalid returned category');
      }

      $this->assertTrue(is_null($result1) || (is_object($result1) && get_class($result1) == \Illuminate\Database\Eloquent\Collection::class));
    }

    //get by name category
    public function testCategoryStoreGetByName() {
        $category = factory(\App\Models\Category::class)->make();

        $result = $this->category->save($category);

        if(is_object($result) && get_class($result) == \App\Models\Error::class) {
            $this->failnow("couldn't save item ", $result);
        }

        $result1 = $this->category->getByName($category->name);

        if(is_object($result1) && get_class($result1) == \App\Models\Error::class) {
            $this->failnow("couldn't get by name", $result1);
        }

        if(is_object($result1) && get_class($result1) != \App\Models\Category::class) {
            $this->failnow('Invalid returned category');
        }

        $this->assertTrue(is_null($result1) || (is_object($result1) && get_class($result1) == \App\Models\Category::class));
    }

}