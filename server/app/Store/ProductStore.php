<?php

namespace App\Store;

use App\Models;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Category;
use App\Models\Uom;
use App\Models\GradeGroup;
use App\Models\FeatureItem;
use App\Models\PriceList;
use App\Models\Grade;
use App\Models\Store;
use App\Models\Error;
use App\Models\Store\SearchProducts;
use DB;

class ProductStore
{
    public function save(Product $product, $featureitem, $pricelistitem, $stores)
    {
        if ($product->id !== null && $product->id !== 0) {
            return Error::NewAppError('store.product.save.existing.app_error', 'ProductStore.save', null,
                "id={$product->id}", StatusBadRequest);
        }

        $product->id = null;

//        try {
//            if (!Category::find($product->category_id)) {
//                return Error::NewAppError('store.stores.category.find.app_error', 'ProductStore.save', null,
//                    "id=" . $product->category_id, StatusBadRequest);
//            }
//        } catch (\Exception $e) {
//            return Error::NewAppError('store.stores.category.find.app_error', 'ProductStore.save', null,
//                "id=" . $product->category_id . $e->getMessage(), StatusInternalServerError);
//        }
//
//        try {
//            if (!ProductType::find($product->product_type_id)) {
//                return Error::NewAppError('store.store.product.type.update.find.app_error', 'ProductStore.save', null,
//                    "id=" . $product->product_type_id, StatusBadRequest);
//            }
//        } catch (\Exception $e) {
//            return Error::NewAppError('store.store.product.type.update.find.app_error', 'ProductStore.save', null,
//                "id=" . $product->product_type_id . $e->getMessage(), StatusInternalServerError);
//        }
//
//        try {
//            if (!Uom::find($product->uom_id)) {
//                return Error::NewAppError('store.store.uoms.update.find.app_error', 'ProductStore.save', null,
//                    "id=" . $product->uom_id, StatusBadRequest);
//            }
//        } catch (\Exception $e) {
//            return Error::NewAppError('store.store.uoms.update.find.app_error', 'ProductStore.save', null,
//                "id=" . $product->uom_id . $e->getMessage(), StatusInternalServerError);
//        }
//
//
//        try {
//            if (!GradeGroup::find($product->grade_group_id)) {
//                return Error::NewAppError('store.store.grade.group.update.find.app_error', 'ProductStore.save', null,
//                    "id=" . $product->grade_group_id, StatusBadRequest);
//            }
//        } catch (\Exception $e) {
//            return Error::NewAppError('store.store.grade.group.update.find.app_error', 'ProductStore.save', null,
//                "id=" . $product->grade_group_id . $e->getMessage(), StatusInternalServerError);
//        }

        try {
            if (!$data = $product->toInstanceArray()) {
                return Error::NewAppError('store.product.save.app_error', 'ProductStore.save', null,
                    'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.product.save.app_error', 'ProductStore.save', null,
                'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            $product = Product::create($data);
            $product_saved = Product::findOrFail($product->id);
            //nếu tồn tại biến $featureitem
            if (isset($featureitem) && is_array($featureitem)) {
                foreach ($featureitem as $key => $value) {
                    try {
                        if (!FeatureItem::find($value)) {
                            return Error::NewAppError('store.store.feature.item.update.find.app_error', 'ProductStore.save', null,
                                "id=" . $value, StatusBadRequest);
                        }
                    } catch (\Exception $e) {
                        return Error::NewAppError('store.store.feature.item.update.find.app_error', 'ProductStore.save', null,
                            "id=" . $value . $e->getMessage(), StatusInternalServerError);
                    }
                }
                $product_saved->featureitem()->sync($featureitem);
            }
            //nếu tồn tại biến $pricelistitem
            if (isset($pricelistitem) && is_array($pricelistitem)) {
                foreach ($pricelistitem as $key => $value) {
                    try {
                        if (!PriceList::find($value['price_list_id'])) {
                            return Error::NewAppError('store.store.price.list.update.find.app_error', 'ProductStore.save', null,
                                "id=" . $value['price_list_id'], StatusBadRequest);
                        }
                    } catch (\Exception $e) {
                        return Error::NewAppError('store.store.price.list.update.find.app_error', 'ProductStore.save', null,
                            "id=" . $value['price_list_id'] . $e->getMessage(), StatusInternalServerError);
                    }
                    try {
                        if (!Grade::find($value['grade_id'])) {
                            return Error::NewAppError('store.store.grade.update.find.app_error', 'ProductStore.save', null,
                                "id=" . $value['grade_id'], StatusBadRequest);
                        }
                    } catch (\Exception $e) {
                        return Error::NewAppError('store.store.grade.update.find.app_error', 'ProductStore.save', null,
                            "id=" . $value['grade_id'] . $e->getMessage(), StatusInternalServerError);
                    }
                }
                $product_saved->pricelistitem()->sync($pricelistitem);
            }

            //nếu tồn tại biến $stores
            if (isset($stores) && is_array($stores)) {
                foreach ($stores as $key => $value) {
                    try {
                        if (!Store::find($value)) {
                            return Error::NewAppError('store.store.stores.update.find.app_error', 'ProductStore.save', null,
                                "id=" . $value, StatusBadRequest);
                        }
                    } catch (\Exception $e) {
                        return Error::NewAppError('store.store.feature.item.update.find.app_error', 'ProductStore.save', null,
                            "id=" . $value . $e->getMessage(), StatusInternalServerError);
                    }
                }
                $product_saved->productstore()->sync($stores);
            }

        } catch (\Exception $e) {
            return Error::NewAppError('store.product.save.app_error', 'ProductStore.save', null, $e->getMessage(), StatusInternalServerError);
        }
        return $product;
    }

    public function update(Product $product, $featureitem, $pricelistitem, $stores)
    {
        if (!$product->id || !is_integer($product->id)) {
            return Error::NewAppError('model.product.is_valid.id.app_error', 'ProductStore.update', null,
                "id={$product->id}", StatusBadRequest);
        }

        $product_saved = Product::find($product->id);

        try {
            if (!$product_saved) {
                return Error::NewAppError('store.stores.product.find.app_error', 'ProductStore.update', null,
                    "id=" . $product->id, StatusBadRequest);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.stores.product.find.app_error', 'ProductStore.update', null,
                "id=" . $product->id . $e->getMessage(), StatusInternalServerError);
        }


//        try {
//            if (!Category::find($product->category_id)) {
//                return Error::NewAppError('store.stores.category.find.app_error', 'ProductStore.update', null,
//                    "id=" . $product->category_id, StatusBadRequest);
//            }
//        } catch (\Exception $e) {
//            return Error::NewAppError('store.stores.category.find.app_error', 'ProductStore.update', null,
//                "id=" . $product->category_id . $e->getMessage(), StatusInternalServerError);
//        }
//
//        try {
//            if (!ProductType::find($product->product_type_id)) {
//                return Error::NewAppError('store.store.product.type.update.find.app_error', 'ProductStore.update',
//                    null, "id=" . $product->product_type_id, StatusBadRequest);
//            }
//        } catch (\Exception $e) {
//            return Error::NewAppError('store.store.product.type.update.find.app_error', 'ProductStore.update',
//                null, "id=" . $product->product_type_id . $e->getMessage(), StatusInternalServerError);
//        }
//
//        try {
//            if (!Uom::find($product->uom_id)) {
//                return Error::NewAppError('store.store.uoms.update.find.app_error', 'ProductStore.update', null,
//                    "id=" . $product->uom_id, StatusBadRequest);
//            }
//        } catch (\Exception $e) {
//            return Error::NewAppError('store.store.uoms.update.find.app_error', 'ProductStore.update', null,
//                "id=" . $product->uom_id . $e->getMessage(), StatusInternalServerError);
//        }
//
//
//        try {
//            if (!GradeGroup::find($product->grade_group_id)) {
//                return Error::NewAppError('store.store.grade.group.update.find.app_error', 'ProductStore.update', null,
//                    "id=" . $product->grade_group_id, StatusBadRequest);
//            }
//        } catch (\Exception $e) {
//            return Error::NewAppError('store.store.grade.group.update.find.app_error', 'ProductStore.update', null,
//                "id=" . $product->grade_group_id . $e->getMessage(), StatusInternalServerError);
//        }

        try {
            if (!$data = $product->toInstanceArray()) {
                return Error::NewAppError('store.product.save.app_error', 'ProductStore.update', null,
                    'empty data to insert', StatusInternalServerError);
            }
        } catch (\Exception $e) {
            return Error::NewAppError('store.product.save.app_error', 'ProductStore.update', null,
                'cannot convert instance to array ', StatusInternalServerError);
        }

        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            $data_product = $product_saved->update($data);

            //nếu tồn tại biến $featureitem
            if (isset($featureitem) && is_array($featureitem)) {
                foreach ($featureitem as $key => $value) {
                    try {
                        if (!FeatureItem::find($value)) {
                            return Error::NewAppError('store.store.feature.item.update.find.app_error',
                                'ProductStore.update', null,
                                "id=" . $value, StatusBadRequest);
                        }
                    } catch (\Exception $e) {
                        return Error::NewAppError('store.store.feature.item.update.find.app_error',
                            'ProductStore.update', null,
                            "id=" . $value . $e->getMessage(), StatusInternalServerError);
                    }
                }
                $product_saved->featureitem()->sync($featureitem);
            }
            //nếu tồn tại biến $pricelistitem
            if (isset($pricelistitem) && is_array($pricelistitem)) {
                foreach ($pricelistitem as $key => $value) {
                    try {
                        if (!PriceList::find($value['price_list_id'])) {
                            return Error::NewAppError('store.store.price.list.update.find.app_error', 'ProductStore.update', null,
                                "id=" . $value['price_list_id'], StatusBadRequest);
                        }
                    } catch (\Exception $e) {
                        return Error::NewAppError('store.store.price.list.update.find.app_error', 'ProductStore.update', null,
                            "id=" . $value['price_list_id'] . $e->getMessage(), StatusInternalServerError);
                    }
                    try {
                        if (!Grade::find($value['grade_id'])) {
                            return Error::NewAppError('store.store.grade.update.find.app_error', 'ProductStore.update', null,
                                "id=" . $value['grade_id'], StatusBadRequest);
                        }
                    } catch (\Exception $e) {
                        return Error::NewAppError('store.store.grade.update.find.app_error', 'ProductStore.update', null,
                            "id=" . $value['grade_id'] . $e->getMessage(), StatusInternalServerError);
                    }
                }
                $product_saved->pricelistitem()->detach();
                $product_saved->pricelistitem()->sync($pricelistitem);
            }

            //nếu tồn tại biến $stores
            if (isset($stores) && is_array($stores)) {
                foreach ($stores as $key => $value) {
                    try {
                        if (!Store::find($value)) {
                            return Error::NewAppError('store.store.stores.update.find.app_error',
                                'ProductStore.update', null,
                                "id=" . $value, StatusBadRequest);
                        }
                    } catch (\Exception $e) {
                        return Error::NewAppError('store.store.stores.update.find.app_error',
                            'ProductStore.update', null,
                            "id=" . $value . $e->getMessage(), StatusInternalServerError);
                    }
                }
                $product_saved->productstore()->sync($stores);
            }

        } catch (\Exception $e) {
            return Error::NewAppError('store.product.update.updating.app_error', 'ProductStore.update',
                null, "id=" . $product->id . ', ' . $e->getMessage(), StatusInternalServerError);
        }

        return $product;

    }

    public function searchProductsForFactory(SearchProducts $searchProducts)
    {
        if (is_null($searchProducts->factory_id)) {
            return Error::NewAppError('model.search_prodcut.app_error', 'ProductStore.searchProductsForFactory',
                null, "", StatusBadRequest);
        }
        if (!is_null($searchProducts->factory_id) && filter_var($searchProducts->factory_id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.product.is_valid.category.app_error', 'ProductStore.searchProductsForFactory',
                null, "name={$searchProducts->factory_id}", StatusBadRequest);
        }
        if (!is_null($searchProducts->featureitem_id) && filter_var($searchProducts->featureitem_id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.product.is_valid.name.app_error', 'ProductStore.getProducts', null,
                "name={$searchProducts->featureitem_id}", StatusBadRequest);
        }

        if (!is_null($searchProducts->category_id) && filter_var($searchProducts->category_id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.product.is_valid.name.app_error', 'ProductStore.getProducts', null,
                "name={$searchProducts->category_id}", StatusBadRequest);
        }

        if (!is_null($searchProducts->code) && !is_string($searchProducts->code)) {
            return Error::NewAppError('model.product.is_valid.code.app_error', 'ProductStore.getProducts', null,
                "name={$searchProducts->code}", StatusBadRequest);
        }

        if (!is_null($searchProducts->code)) {
            $searchProducts->code = urldecode($searchProducts->code);
        }
        try {
            $result = DB::table('products')
                ->join('product_stores','products.id','product_stores.product_id')
                ->join('stores','product_stores.store_id','stores.id')
                ->join('categories','products.category_id','categories.id')
                ->join('factories','stores.factory_id','factories.id')
                ->where('stores.factory_id', $searchProducts->factory_id)
                ->where('products.active', ACTIVE_TRUE);

            if (!is_null($searchProducts->featureitem_id)) {

                $result = $result->join('product_featureitem','product_featureitem.product_id','products.id')
                    ->select('product_featureitem.featureitem_id as featureitem_id')
                    ->where('product_featureitem.featureitem_id', $searchProducts->featureitem_id);
            }


            if (!is_null($searchProducts->category_id)) {
                 $result = $result->where('products.category_id', $searchProducts->category_id);
            }

            if(!is_null($searchProducts->code)) {
                $result = $result->where('products.code' , 'LIKE', "%$searchProducts->code%");
            }

            $result = $result->select('products.*',
                'stores.name as store_name',
                'categories.name as category_name',
                'factories.name as factory_name','factories.id as factory_id'
               )->orderBy('products.id', 'desc');

            if (!is_null($searchProducts->limit)) {
                $result = $result->paginate($searchProducts->limit);
            } else {
                $result = $result->get();
            }
            return $result;
        } catch (\Exception $e) {
            return Error::NewAppError('store.product.search_by_factory_id.app_error', 'ProductStore.searchProductsForFactory',
                null, $e->getMessage(), StatusInternalServerError);
        }
    }

    public function getProducts($brand, $category, $code, $limit = 20)
    {
        if (!is_null($category) && filter_var($category, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.product.is_valid.category.app_error', 'ProductStore.getProducts', null,
                "name={$category}", StatusBadRequest);
        }
        if (!is_null($brand) && filter_var($brand, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.product.is_valid.name.app_error', 'ProductStore.getProducts', null,
                "name={$brand}", StatusBadRequest);
        }
        if (!is_null($code) && !is_string($code)) {
            return Error::NewAppError('model.product.is_valid.code.app_error', 'ProductStore.getProducts', null,
                "name={$code}", StatusBadRequest);
        }

        $code = urldecode($code);
        try {
            $result = new Product();
            if ($brand) {
                $result = $result->where('brand_id', $brand);
            }

            if ($category) {
                $result = $result->where(function ($q) use ($category) {
                    $q->where('category_id', $category);
                });
            }

            if ($code) {
                $result = $result->where('code', 'LIKE', "%$code%");
            }

            $result = $result->with(['category'])->orderBy('id', 'desc');

            $result = $result->paginate($limit);


            return $result;

        } catch (\Exception $e) {
            return Error::NewAppError('store.area.get_by_name.app_error', 'AreaStore.getByName', ['Name' => $brand],
                'name=' . $brand . ', ' . $e->getMessage(), StatusInternalServerError);
        }
    }

    public function getDetail($product_id, $factory_id)
    {
        if (filter_var($product_id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.product.is_valid.id.app_error', 'ProductStore.get', null,
                "id={$product_id}", StatusBadRequest);
        }
        if (filter_var($factory_id, FILTER_VALIDATE_INT) === false) {
            return Error::NewAppError('model.product.is_valid.id.app_error', 'ProductStore.get', null,
                "id={$factory_id}", StatusBadRequest);
        }

        try {
//            $product = $result->with('category','productstore')
//                ->whereHas('productstore', function ($q) use($factory_id) {
//                    $q->where('factory_id', $factory_id);
//                })->where('id', $product_id)->first();
        $product = DB::table('products')
            ->join('product_stores','products.id','product_stores.product_id')
            ->join('stores','product_stores.store_id','stores.id')
            ->join('categories','products.category_id','categories.id')
            ->join('factories','stores.factory_id','factories.id')
            ->join('product_types','products.product_type_id','product_types.id')
            ->where('products.id', $product_id)
            ->where('stores.factory_id', $factory_id)
            ->select('products.*',
                'stores.name as store_name',
                'stores.code as store_code',
                'product_types.name as product_type_name',
                'categories.name as category_name',
                'factories.name as factory_name','factories.id as factory_id')
            ->first();
        return $product;
        } catch (\Exception $e) {

            return Error::NewAppError('store.product.get.finding.app_error', 'ProductStore.get', null,
                "id=" . $product_id . ', ' . $e->getMessage(), StatusInternalServerError);

        }

        if ($product == null) {
            return Error::NewAppError('store.product.get.finding.app_error', 'ProductStore.get', null,
                "id={$product_id}", StatusNotFound);

        }

        return $product;
    }
}
