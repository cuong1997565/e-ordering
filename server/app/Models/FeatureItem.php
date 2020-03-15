<?php
namespace App\Models;

use App\Store\FeatureItemStore;

class FeatureItem extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','feature_id', 'name', 'display_name','sequence','is_active','active'
    ];

    public $belongTo =
        [
            'feature'
    ];

    public function feature() {
        return $this->belongsTo(Features::class,'feature_id')->select('id','name');
    }
    /**
     * $this :'id','feature_id', 'name', 'display_name','sequence','is_active'
     *
     * @var array
     */
    public function createFeatureItem() {
        $this->id = null;

        $result = (new FeatureItemStore())->save($this);

        return $result;
    }

    /**
     * $this :'id','feature_id', 'name', 'display_name','sequence','is_active'
     *
     * @var array
     */
    public function updateFeatureItems() {
        $result = (new FeatureItemStore())->update($this);

        return $result;
    }
}
