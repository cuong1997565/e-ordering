<?php
namespace App\Dashboard\ReadModel\Implementation\Mysql;

use App\Models\Distributor;
use Illuminate\Database\Eloquent\Model;

class DashBoardListDbContext extends Model {
    public $table = 'dashboard_lists';

    protected $fillable = ['id', 'value','type'];

}

