<?php
namespace App\Models;

class Translation extends AppModel
{
    protected $fillable = [
        'key','lang','trans','type'
    ];
}