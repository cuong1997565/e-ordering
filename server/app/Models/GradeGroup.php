<?php
namespace App\Models;

use App\Store\GradeGroupStore;

class GradeGroup extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'code','active'
    ];

    /**
     * $this : 'name', 'code'
     *
     * @var array
     */

    public function createGradeGroup() {
        $this->id = null;

        $result = (new GradeGroupStore())->save($this);

        return $result;
    }

    /**
     * $this : 'name', 'code'
     *
     * @var array
     */
    public function updateGradeGroup() {
        $result = (new GradeGroupStore())->update($this);

        return $result;
    }
}
