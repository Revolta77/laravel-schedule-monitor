<?php

namespace Revolta77\ScheduleMonitor\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;

use Schema;

class Kernel
{

    /**
     * Define the application's command schedule with Scheduler service.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    public function schedule(Schedule $schedule)
    {
		$this->standard($schedule);
    }

    /**
     * Standard command schedule. used when service is disabled.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    private function standard(Schedule $schedule)
    {
        $this->tasks($schedule);
    }

}
