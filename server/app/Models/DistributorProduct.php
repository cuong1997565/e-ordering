<?php
namespace App\Models;

class DistributorProduct extends AppModel
{
    protected $table = 'distributor_product';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'product_id','distributor_id', 'min_quantity', 'max_quantity', 'max_hold_age'
    ];
}
