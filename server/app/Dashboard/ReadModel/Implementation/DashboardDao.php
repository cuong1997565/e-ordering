<?php
namespace App\Dashboard\ReadModel\Implementation;

use App\Dashboard\ReadModel\Dashboard;
use App\Dashboard\ReadModel\IDashboardDao;
use App\Dashboard\ReadModel\Implementation\Mysql\DashboardDbContext;
use App\Dashboard\ReadModel\Implementation\Mysql\DashBoardListDbContext;
use Illuminate\Support\Facades\DB;

class DashboardDao implements IDashboardDao {
    public $dbContext;
    public $dbListContext;

    public function __construct(DashboardDbContext $context, DashBoardListDbContext $dashBoardListDbContext)
    {
        $this->dbContext = $context;
        $this->dbListContext = $dashBoardListDbContext;
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
        $dashboard = $this->dbContext->with('items')->get();
        $result = [];
        foreach ($dashboard as $item) {
            $result[] = new Dashboard(
                $item->id,
                $item->value,
                $item->type,
                $item->items
            );
        }

        return $result;
    }

    public function getCreditLimitExpired()
    {
        $creditLimitExpired = $this->dbListContext->get();
        return $creditLimitExpired;
    }
}
