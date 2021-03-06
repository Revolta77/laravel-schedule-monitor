<?php

namespace Revolta77\ScheduleMonitor;

use Illuminate\Support\ServiceProvider;

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

//			app('Revolta77\ScheduleMonitor\Conntroller\CreateController')->index();

//			$this->commands([
//				Console\Commands\CreateController::class,
//			]);
		}
		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }
}
