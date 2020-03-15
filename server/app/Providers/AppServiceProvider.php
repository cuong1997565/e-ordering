<?php

namespace App\Providers;

use App\Dashboard\ReadModel\IDashboardDao;
use App\Dashboard\ReadModel\Implementation\DashboardDao;
use App\Dashboard\ReadModel\Implementation\Mysql\DashboardDbContext;
use App\Dashboard\ReadModel\Implementation\Mysql\DashBoardListDbContext;
use App\Helper;
use App\Models\Customer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {
        $this->app->singleton('helper', function () {
            return new Helper();
        });
        $this->app->singleton('uuid', function () {
            return uniqid();
        });
        $this->app->bind(IDashboardDao::class, function () {
            $dashboardContext = new DashboardDbContext();
            $dashboardListContext = new DashBoardListDbContext();
            return new DashboardDao($dashboardContext, $dashboardListContext);
        });
    }

}
