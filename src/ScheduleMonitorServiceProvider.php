<?php

namespace Revolta77\ScheduleMonitor;

use Illuminate\Support\ServiceProvider;
use Revolta77\ScheduleMonitor\Controllers\CreateController;

class ScheduleMonitorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
		if ( $this->app->runningInConsole() ) {
			$this->loadMigrationsFrom(__DIR__.'/migrations');

			$this->publishes([
				__DIR__.'/config/schedule-monitor.php'      => config_path('schedule-monitor.php'),
//				__DIR__.'/console/CronTasksList.php' => app_path('Console/CronTasksList.php'),
//				__DIR__.'/views'                     => resource_path('views/vendor/scheduler'),
			]);


			$create = CreateController::create();

//			$this->commands([
//				Console\Commands\CreateController::class,
//			]);
		}
    }
}
