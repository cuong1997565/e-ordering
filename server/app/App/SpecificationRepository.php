<?php

namespace App\App;

use App\Store\SpecificationStore;

class SpecificationRepository
{
    public function createSpecification($specification)
    {
        $result = (new SpecificationStore())->save($specification);

        return $result;
    }

    public function updateSpecification($specification)
    {
        $result = (new SpecificationStore())->update($specification);

        return $result;
    }
}
