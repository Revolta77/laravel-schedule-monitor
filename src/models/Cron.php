<?php

namespace Revolta77\ScheduleMonitor;

use Illuminate\Database\Eloquent\Model;

class Cron extends Model {
	protected $table = 'crons';

	public $timestamps = true;

	protected $fillable = [
		'command', 'success', 'error', 'description', 'parameters', 'expression', 'timezone',
		'run_at', 'necxt_run_at', 'is_active', 'overlaps', 'maintenance', 'created_at', 'updated_at'
	];
}
