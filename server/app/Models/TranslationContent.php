<?php
namespace App\Models;

class TranslationContent extends AppModel
{
    protected $fillable = [
        'table','table_id','table_field','lang','trans'
    ];
}