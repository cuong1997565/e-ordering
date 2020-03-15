<?php

namespace App\Models;

use App\Store\ProductStore;


class Product extends AppModel
{
    protected $fillable = [
        'id',
        'category_id',
        'code',
        'name',
        'image',
        'product_type_id',
        'uom_id',
        'grade_group_id',
        'short_name',
        'display_name',
        'is_life_management',
        'max_age',
        'release_date',
        'active'
    ];

    public $files = ['image' => ['ext' => 'jpeg|png|jpg']];


    public $belongTo =
        [
            'category'
        ];

    /**
     * create product
     * $this :
        'id','category_id','code','name','image','product_type_id','uom_id','grade_group_id',
        'short_name','display_name','is_life_management','max_age','release_date','active'
     *
     * @var array
     * $featureitem : ["2","6"]
     * $pricelistitem : [
        {
            "price_list_id": "10",
            "product_id": "20",
            "grade_id": "30",
            "unit_price": "50000"
            },
            {
            "price_list_id": "34",
            "product_id": "15",
            "grade_id": "190000000",
            "unit_price": "20000"
        }
    ]
     * $stores : ["20","24"]
     */
    public function createProduct($featureitem = null, $pricelistitem = null, $stores = null)
    {
        $this->id = null;
        $result = (new ProductStore())->save($this, $featureitem , $pricelistitem, $stores);

        return $result;
    }


    /**
         * update product
         * $this :
        'id','category_id','code','name','image','product_type_id','uom_id','grade_group_id',
        'short_name','display_name','is_life_management','max_age','release_date','active'
         *
         * @var array
         * $featureitem : ["2","6"]
         * $pricelistitem : [
        {
        "price_list_id": "10",
        "product_id": "20",
        "grade_id": "30",
        "unit_price": "50000"
        },
        {
        "price_list_id": "34",
        "product_id": "15",
        "grade_id": "190000000",
        "unit_price": "20000"
        }
        ]
     *  $stores : [2,6]
     */

    public function updateProduct($featureitem = null, $pricelistitem = null, $stores = null) {
        $result = (new ProductStore())->update($this, $featureitem, $pricelistitem, $stores);

        return $result;
    }


    public function featureitem()
    {
        return $this->belongsToMany(FeatureItem::class, 'product_featureitem',
            'product_id', 'featureitem_id');
    }

    public function price_list_items() {
        return $this->hasMany(PriceListItem::class);
    }

    public function pricelistitem()
    {
        return $this->belongsToMany(PriceList::class, 'price_list_items', 'product_id','price_list_id')
            ->withPivot(['price_list_id', 'grade_id','unit_price']);
    }

    public function productstore()
    {
        return $this->belongsToMany(Store::class, 'product_stores',
            'product_id', 'store_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->select('id','name');
    }


    public function sale_order_items()
    {
        return $this->hasOne(SaleOrderItem::class, 'product_id');
    }

    public  function getDetail($product_id, $factory_id)
    {
        $productOrError = (new ProductStore())->getDetail($product_id, $factory_id);

        if ((is_object($productOrError)) && get_class($productOrError) == Error::class) {

            $productOrError->StatusCode = StatusNotFound;

        }

        return $productOrError;
    }

    public function order_products()
    {
        $this->hasMany(OrderProduct::class, 'product_id');
    }

}
