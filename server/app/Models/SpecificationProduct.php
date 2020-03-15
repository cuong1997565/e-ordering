<?php

namespace App\Models;

class SpecificationProduct extends AppModel
{
    protected $fillable = [
        'id',
        'product_id',
        'specification_id',
        'parent_specification'
    ];
}
