<?php

namespace Revolta77\ScheduleMonitor\Console;


use Illuminate\Console\Scheduling\Event;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Revolta77\ScheduleMonitor\MonitorsSchedule;


class Kernel extends ConsoleKernel
{
	use MonitorsSchedule;

	/**
	 * Define the application's command schedule with Scheduler service.
	 *
	 * @param \Illuminate\Console\Scheduling\Schedule $schedule
	 *
	 * @return void
	 */
	public function schedule(Schedule $schedule)
	{
		$this->tasks($schedule);
	}

}