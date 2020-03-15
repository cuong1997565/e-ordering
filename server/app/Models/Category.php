<?php

namespace App\Models;

use App\Store\CategoryStore;


class Category extends AppModel
{
    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'active',
        'level',
        'code'
    ];

    public function categorys() {
        return $this->hasMany(Category::class,'parent_id');
    }


    public function category_level_tow() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function category_level_three() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function category_level_four() {
        return $this->hasMany(Category::class, 'parent_id');
    }



    public function createCategory()
    {
        $this->id = null;

        $result = (new CategoryStore())->save($this);

        return $result;
    }

    public function updateCategory()
    {
        $result = (new CategoryStore())->update($this);

        return $result;
    }

    public function searchCategoriesByName($name)
    {
        $categoryOrError = (new CategoryStore())->searchByName($name);

        return $categoryOrError;

    }

    public function getCategoryByName($name)
    {
        $categoryOrError = (new CategoryStore())->getByName($name);

        if ((is_object($categoryOrError)) && get_class($categoryOrError) == Error::class) {

            $categoryOrError->StatusCode = StatusNotFound;

        }

        return $categoryOrError;
    }
}

