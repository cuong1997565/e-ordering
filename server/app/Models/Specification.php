<?php

namespace App\Models;

use App\App\SpecificationRepository;

class Specification extends AppModel
{
    protected $fillable = [
        'id',
        'parent_id',
        'name',
    ];

    public function specifications() {
        return $this->hasMany(Specification::class,'parent_id');
    }

    public function childrens() {
        return $this->hasMany(Specification::class,'parent_id');
    }

    public function createSpecification()
    {
        $this->id = null;

        $result = (new SpecificationRepository())->createSpecification($this);

        return $result;
    }

    public function updateSpecification()
    {
        $result = (new SpecificationRepository())->updateSpecification($this);

        return $result;
    }
}
