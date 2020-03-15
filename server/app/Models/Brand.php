<?php

namespace App\Models;
use App\Store\BrandStore;

class Brand extends AppModel
{
    protected $fillable = [
        'id',
        'name',
        'code',
        'active',
    ];

    public function createBrand()
    {
        $this->id = null;

        $result = (new BrandStore())->save($this);

        return $result;
    }

    public function updateBrand()
    {
        $result = (new BrandStore())->update($this);

        return $result;
    }

    public function searchBrandsByName($name)
    {
        $brandOrError = (new BrandStore())->searchByName($name);

        return $brandOrError;

    }

    public  function getBrandByName($name)
    {
        $brandOrError = (new BrandStore())->getByName($name);

        if ((is_object($brandOrError)) && get_class($brandOrError) == Error::class) {

            $brandOrError->StatusCode = StatusNotFound;

        }

        return $brandOrError;
    }
}
