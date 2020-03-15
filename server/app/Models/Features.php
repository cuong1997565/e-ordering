<?php
namespace App\Models;

use App\Store\FeaturesStore;

class Features extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'display_name','active'
    ];
    /**
     * $this :  'name', 'display_name'
     *
     * @var array
     */
    public function createFeatures() {
        $this->id = null;

        $result = (new FeaturesStore())->save($this);

        return $result;
    }

    /**
     * $this :  'name', 'display_name'
     *
     * @var array
     */
    public function updateFeatures() {
        $result = (new FeaturesStore())->update($this);

        return $result;
    }
}
