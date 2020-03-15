<?php
namespace App\Models;

class Contact extends AppModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','phone_number','email','content'
    ];
}