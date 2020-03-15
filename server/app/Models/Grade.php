<?php
namespace App\Models;

use App\Store\GradeStore;

class Grade extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'grade_group_id',
        'code',
        'name',
        'display_name',
        'active'
    ];

    public $belongTo =
    [
            'gradegroup',
    ];

    public function gradegroup() {
        return $this->belongsTo(GradeGroup::class, 'grade_group_id');
    }

    /**
     * create grade
     * $this :
     * 'grade_group_id',
     * 'code',
    'name',
    'display_name'
     *
     * @var array
     */

    public function createGrade() {
        $this->id = null;

        $result = (new GradeStore())->save($this);

        return $result;
    }

     /**
      * update grade
     * $this :
     'grade_group_id',
    'code',
    'name',
    'display_name'
     *
     * @var array
     */
    public function updateGrade() {
        $result = (new GradeStore())->update($this);

        return $result;
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class, 'grade_id');
    }

    public function sale_order_items()
    {
        return $this->hasMany(SaleOrderItem::class, 'grade_id');
    }
}
