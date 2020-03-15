<?php
namespace App\Models;

class Sample extends AppModel
{
    protected $fillable =
    [
        'sample_type_id','name','image','avatar','gallery','description','content','active',
    ];

    protected $hidden =
    [

    ];

    public $files =
    [
        'image'=>
        [
            'ext'=>'jpg|jpeg|png',
            'size'=>'5120',
            'multiple'=>false
        ]
    ];

    public $belongTo =
    [
        'sample_type',
    ];

    public $hasMany =
    [
        'sample_items',
    ];

    public function sample_type()
    {
        return $this->belongsTo('App\Models\SampleType','sample_type_id');
    }

    public function sample_items()
    {
        return $this->hasMany('App\Models\SampleItem', 'sample_id');
    }
}