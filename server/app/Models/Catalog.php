<?php

namespace App\Models;

class Catalog extends AppModel
{
    protected $fillable = [
      'id',
      'name',
      'file'
    ];

    public $files =
        [
            'file'=>
                [
                    'ext'=>'xls|xlsx|pdf',
                    'size'=>'5120',
                    'multiple'=>false
                ]
        ];
}
