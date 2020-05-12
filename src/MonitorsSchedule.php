<?php

namespace Revolta77\ScheduleMonitor;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Revolta77\ScheduleMonitor\Cron;

trait MonitorsSchedule
{
	/**
	 * Monitor each of the scheduled events.
	 *
	 * @param Schedule $schedule
	 */
	public function monitor(Schedule $schedule)
	{

		$date = Carbon::today()->toDateString();

		$events = new Collection($schedule->events());

		$events->each(function (Event $event) use ($date) {

			$command = str_after($event->command, '\'artisan\' ');
			$filename = str_slug($command) . "-$date.log";
			$path = storage_path("logs/$filename");

			$event->sendOutputTo($path)->after(function () use ($command, $path) {

				if (file_exists($path) && ($output = file_get_contents($path))) {

					$start = $end = NULL;
					$json = json_decode($output, true);

					if( !empty( $json ) ){
						// cron success
						$start = $json['start'];
						$end = $json['end'];
						$output = $json['output'];
						Cron::where( 'command', $command )->increment( 'success', 1);
					} else {
						Cron::where( 'command', $command )->increment( 'error', 1);
					}

					DB::table('cron_loggers')->insert([
						'command'   => $command,
						'start'     => $start,
						'end'		=> $end,
						'output'    => $output,
						'created_at' => Carbon::now(),
					]);

					unlink($path);
				}
			});
		});
	}
}
