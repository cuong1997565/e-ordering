<?php
namespace App\Dashboard\ReadModel\Implementation\Mysql;

use Illuminate\Database\Eloquent\Model;

class DashboardItemDbContext extends Model {
    public $table = 'dashboard_items';

    protected $fillable = ['id', 'value'];
}
