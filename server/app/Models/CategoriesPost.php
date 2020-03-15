<?php
namespace App\Models;

class CategoriesPost extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id', 'post_id', 'order'
    ];

}