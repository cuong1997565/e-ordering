<?php
namespace App\Dashboard\ReadModel\Implementation\Mysql;

use App\Dashboard\ReadModel\Dashboard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DashboardDbContext extends Model {
    public $table = 'dashboards';

    protected $fillable = ['id', 'value', 'type'];

    public function items() {
        return $this->hasMany(DashboardItemDbContext::class, 'dashboard_id', 'id');
    }
}
