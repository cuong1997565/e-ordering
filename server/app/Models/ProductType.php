<?php
namespace App\Models;

use App\Store\ProductTypeStore;

class ProductType extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'code', 'description', 'active'
    ];

    /**
     * $this :  'name', 'code', 'description'
     *
     * @var array
     */
    public function createProductType() {
        $this->id = null;

        $result = (new ProductTypeStore())->save($this);

        return $result;
    }

    /**
     * $this :  'name', 'code', 'description'
     *
     * @var array
     */
    public function updateProductType() {
        $result = (new ProductTypeStore())->update($this);

        return $result;
    }
}
