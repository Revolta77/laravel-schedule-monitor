<?php

namespace Revolta77\ScheduleMonitor;

use Illuminate\Support\ServiceProvider;
use Revolta77\ScheduleMonitor\Console\Commands\Create;

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

//			$this->publishes([
//				__DIR__.'/config/scheduler.php'      => config_path('scheduler.php'),
//				__DIR__.'/console/CronTasksList.php' => app_path('Console/CronTasksList.php'),
//				__DIR__.'/views'                     => resource_path('views/vendor/scheduler'),
//			]);

			$this->commands([
				Console\Commands\Create::class,
			]);
		}
    }
}
