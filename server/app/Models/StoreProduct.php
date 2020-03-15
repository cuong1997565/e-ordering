<?php
namespace App\Models;
class StoreProduct extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'product_id','store_id'
    ];
}
