<?php

namespace App\Providers;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use App\Providers\Logger\Logger;
class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('logger', function () {
            $logManager = app()->make('log');
            return new Logger($logManager);
        });
    }
}
