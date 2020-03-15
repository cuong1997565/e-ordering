<?php
namespace App\Models;
use App\Store\AreaStore;

class Area extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','parent_id', 'name', 'code','level','active'
    ];

    public function createArea()
    {
       $this->id = null;

       $result = (new AreaStore())->save($this);

       return $result;
    }

    public function updateArea()
    {
        $result = (new AreaStore())->update($this);
        return $result;
    }

    public function getAreaByName($name)
    {
        $areaOrError = (new AreaStore())->getByName($name);
        if ((is_object($areaOrError)) && get_class($areaOrError) == Error::class) {
            $areaOrError->StatusCode = StatusNotFound;
        }

        return $areaOrError;
    }

    public function searchAreaByname($name)
    {
        $customerOrError = (new AreaStore())->searchByName($name);
        return $customerOrError;
    }


}
