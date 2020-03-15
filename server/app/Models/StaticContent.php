<?php
namespace App\Models;

class StaticContent extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'description', 'content', 'active'
    ];

}