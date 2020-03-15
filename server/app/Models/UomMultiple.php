<?php
namespace App\Models;
use App\Store\UomMultiplesStore;

class UomMultiple extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uom_id',
        'code',
        'name',
        'display_name',
        'description',
        'conversion_rate',
        'isrounded',
        'round_priority',
        'active'
    ];

    public $belongTo =
        [
            'uom',
        ];

    public function uom() {
        return $this->belongsTo(Uom::class,'uom_id');
    }


    /**
     * $this :
     * 'uom_id',
    'code',
    'name',
    'display_name',
    'description',
    'conversion_rate',
    'isrounded',
    'round_priority'
     *
     * @var array
     */
    public function createUomMultiples() {
        $this->id = null;

        $result = (new UomMultiplesStore())->save($this);

        return $result;
    }

    /**
     * $this :
     * 'uom_id',
    'code',
    'name',
    'display_name',
    'description',
    'conversion_rate',
    'isrounded',
    'round_priority'
     *
     * @var array
     */

    public function updateUomMultiple() {
        $result = (new UomMultiplesStore())->update($this);

        return $result;
    }
}
