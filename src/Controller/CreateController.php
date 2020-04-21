<?php

namespace Revolta77\ScheduleMonitor\Controllers;

use App\Console\ScheduleService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Contracts\Console\Kernel as kernel;
use Illuminate\Http\Request;
use Revolta77\ScheduleMonitor\Cron;
use Illuminate\Support\Facades\Schema;
use Illuminate\Console\Scheduling\Schedule;

class CreateController extends Controller
{
	private $consoleKernel;

	public function __construct(kernel $consoleKernel) {
		$this->consoleKernel = $consoleKernel;
	}

	public function index() {
		if ( !Schema::hasTable('crons') && !Schema::hasTable('cron_loggers') ) {
			echo "Tables for scheduled tasks does not exist. /n";
			echo "Run 'php artisan migrate'. /n";
			exit;
		}

		app()->make(\Illuminate\Contracts\Console\Kernel::class);
		$schedule = app()->make(\Illuminate\Console\Scheduling\Schedule::class);

		$events = collect($schedule->events())->map(function($event) {
			$cron = CronExpression::factory($event->expression);
			$date = Carbon::now();
			if ($event->timezone) {
				$date->setTimezone($event->timezone);
			}
			$next = $cron->getNextRunDate()->format('Y-m-d H:i:s');
//			dd($next);
			$prev = $cron->getPreviousRunDate()->format('Y-m-d H:i:s');
			$mid      = (strtotime($next) + strtotime($prev)) / 2;
			$mid       = round($mid);
			$run = date('Y-m-d H:i:s', $mid);
			$command = str_after($event->command, '\'artisan\' ');
			return (object)[
				'expression' => $event->expression,
				'command' => $command,
				'run_at' => $run,
				'next_run_at' => $next,
				'description'   => $this->getDescriptionFromCommand($command) ?: NULL,
				'timezone'      => $event->timezone ?: config('app.timezone', 'UTC'),
				'overlaps'      => $event->withoutOverlapping ? '1' : '0',
				'maintenance'   => $event->evenInMaintenanceMode ? '1' : '0',
			];
		});

		if( !empty( $events ) ) foreach ( $events as $event ){
//			dd($event);
			Cron::query()->update([ 'is_active' => 0 ]);
			Cron::updateOrCreate([ 'command' => $event->command ], [
				'success' => 0,
				'error' => 0,
				'description' => $event->description,
				'expression' => $event->expression,
				'timezone' => $event->timezone,
				'run_at' => $event->run_at,
				'next_run_at' => $event->next_run_at,
				'is_active' => 1,
				'overlaps' => $event->overlaps,
				'maintenance' => $event->maintenance,
				'created_at' => Carbon::now(),
			]);
		}

		echo "Tasks successfully created. /n";
	}

	private function getDescriptionFromCommand(string $commandName): string
	{
		$commands = $this->consoleKernel->all();
		if (!isset($commands[$commandName])) {
			return '';
		}

		try {
			$className = get_class($commands[$commandName]);
			$reflection = new \ReflectionClass($className);
			return (string)$reflection->getDefaultProperties()['description'];
		} catch (\ReflectionException $exception) {
			return '';
		}
	}

}
