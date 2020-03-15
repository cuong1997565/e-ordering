<?php
namespace App\Models;
use App\Store\AttributesStore;

class Attributes extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'product_type_id',
        'code',
        'name',
        'description',
        'attribute_label',
        'type',
        'validate_mask',
        'sequence',
        'active'
    ];

    public $belongTo =
        [
            'producttype'
        ];

    public function producttype() {
        return $this->belongsTo(ProductType::class,'product_type_id');
    }


    public function attributelist() {
        return $this->hasMany(AttributeListsOfValue::class,'attribute_id');
    }


    /**
     * $this :
     'id',
    'product_type_id',
    'code',
    'name',
    'description',
    'attribute_label',
    'type',
    'validate_mask',
    'sequence'
     *
     * @var array
     * create attributes
     */
    public function createAttributes() {
        $this->id = null;

        $result = (new AttributesStore())->save($this);

        return $result;
    }

    /**
     * $this :
    'id',
    'product_type_id',
    'code',
    'name',
    'description',
    'attribute_label',
    'type',
    'validate_mask',
    'sequence'
     *
     * @var array
     * update attributes
     */
    public function updateAttributes() {
        $result = (new AttributesStore())->update($this);
        return $result;
    }
}
