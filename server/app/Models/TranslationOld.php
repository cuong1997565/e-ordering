<?php
namespace App\Models;

class TranslationOld extends AppModel
{
    protected $connection = 'mysql_server_translations';
    protected $table = 'translations';
    protected $fillable = [
        'key','lang','trans','type'
    ];
}