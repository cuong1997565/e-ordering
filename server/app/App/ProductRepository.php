<?php
namespace App\App;

use App\Models\ProductSearchForFactory;
use App\Models\Store\SearchProducts;
use App\Store\ProductStore;

class ProductRepository{
    public function getProducts($brand, $category, $code)
    {
        $result = (new ProductStore())->getProducts($brand, $category, $code);

        return $result;
    }

    public function searchProductsForFactory(SearchProducts $search)
    {
        $result = (new ProductStore())->searchProductsForFactory($search);

        return $result;
    }

}
