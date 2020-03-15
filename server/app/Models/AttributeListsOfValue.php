<?php
namespace App\Models;
use App\Store\AttributeListsOfValueStore;

class AttributeListsOfValue extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'attribute_id',
        'value',
        'active'
    ];

    public $belongTo =
        [
            'attributes'
        ];


    public function attributes() {
        return $this->belongsTo(Attributes::class, 'attribute_id')->select(['id','name']);
    }

    /**
     * $this :
        'attribute_id',
        'value'
     *
     * @var array
     */
    public function createAttributeListsOfValue() {
        $this->id = null;

        $result = (new AttributeListsOfValueStore())->save($this);

        return $result;
    }

    /**
     * $this :
     'id',
    'attribute_id',
    'value'
     *
     * @var array
     */
    public function updateAttributeListsOfValue() {
        $result = (new AttributeListsOfValueStore())->update($this);

        return $result;
    }
}
