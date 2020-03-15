<?php
namespace App\Models;

class AreaProduct extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'product_id','area_id'
    ];
}
