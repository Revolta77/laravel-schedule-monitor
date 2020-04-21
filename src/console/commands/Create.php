<?php

namespace Revolta77\ScheduleMonitor\Console\Commands;

use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Revolta77\ScheduleMonitor\Cron;

class Create extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'schedule-monitor:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create scheduled tasks in database from standard applications tasks';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		if ( !Schema::hasTable('crons') && !Schema::hasTable('cron_loggers') ) {
			$this->error('Tables for scheduled tasks does not exist.');
			$this->error("Run 'php artisan migrate'.");
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
			$prev = $cron->getPreviousRunDate()->format('Y-m-d H:i:s');
			$mid      = (strtotime($next) + strtotime($prev)) / 2;
			$mid       = round($mid);
			$run = date('Y-m-d H:i:s', $mid);
			$command = str_after($event->command, '\'artisan\' ');
			return (object)[
				'expression' => $event->expression,
				'command' => $command,
				'next_run_at' => $next,
				'run_at' => $run,
				'description'   => $this->description($command) ?: NULL,
				'timezone'      => $event->timezone ?: config('app.timezone', 'UTC'),
				'overlaps'      => $event->withoutOverlapping ? '0' : '1',
				'maintenance'   => $event->evenInMaintenanceMode ? '0' : '1',
			];
		});

		if( !empty( $events ) ) foreach ( $events as $event ){
			Cron::update([ 'is_active' => 0 ]);
			Cron::updateOrCreate([ 'command' => $event->command ], [
				'success' => 0,
				'error' => 0,
				'description' => $event->description,
				'parameters' => $event->parameters,
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

		$this->info('Tasks successfully created.');
	}

	private function description($command) {
		$className = get_class($this->getApplication()->find($command));
		$reflection = new \ReflectionClass($className);

		return (string) $reflection->getDefaultProperties()['description'];
	}
}
