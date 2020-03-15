<?php
namespace App\Models;

class ProductFeatureItem extends AppModel
{
    protected $table = 'product_featureitem';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_id',
        'featureitem_id'
    ];
}
