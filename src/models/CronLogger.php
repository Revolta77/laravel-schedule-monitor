<?php

namespace Revolta77\ScheduleMonitor;

use Illuminate\Database\Eloquent\Model;

class CronLogger extends Model {
	protected $table = 'cron_loggers';

	public $timestamps = true;

	protected $fillable = [
		'command', 'start', 'end', 'output', 'created_at', 'updated_at'
	];
}
