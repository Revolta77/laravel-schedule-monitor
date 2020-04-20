# laravel-schedule-monitor-extended
Track the output of your scheduled tasks in a database table.

## Fork
This is extend version of https://github.com/mirzabusatlic/laravel-schedule-monitor

## Installation

1. Install through composer: `composer require revolta77/laravel-schedule-monitor`
2. Add `Revolta77\ScheduleMonitor\ScheduleMonitorServiceProvider::class` to your list of `$providers` in to your `config/app.php`.
3. Publish the migration using `php artisan vendor:publish --provider=Revolta77\\ScheduleMonitor\\ScheduleMonitorServiceProvider`.
4. Run `php artisan migrate` to create tables in your database.

## Usage

- In your `app/Console/Kernel.php`, include the `Revolta77\ScheduleMonitor\MonitorsSchedule` trait.
- Call `$this->monitor($schedule)` after you've defined your scheduled commands in `schedule()`.

This will look something like:

```php
<?php

namespace App\Console;

use Revolta77\ScheduleMonitor\MonitorsSchedule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    use MonitorsSchedule;

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\DeleteFilesCommand::class,
        \App\Console\Commands\FlushEventsCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('files:delete')->dailyAt('00:05');
        
        $schedule->command('events:flush')->hourly();

        $this->monitor($schedule);
    }
}
```

Whenever a scheduled command is then run, the its output will be inserted into table.

| Logged | Command | Output
|---|---|---|
| 2016-07-11 02:21:38 | files:delete | Deleted (6391/6391) total files.
