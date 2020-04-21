<?php

return [
	// add from https://crontab.guru/examples.html
	'expressions' => [
		'* * * * *'      => 'Every minute',
		'*/2 * * * *'    => 'Every 2 minutes',
		'1-59/2 * * * *' => 'Every uneven minute',
		'*/3 * * * *'    => 'Every 3 minutes',
		'*/4 * * * *'    => 'Every 4 minutes',
		'*/5 * * * *'    => 'Every 5 minutes',
		'*/10 * * * *'   => 'Every 10 minutes',
		'*/15 * * * *'   => 'Every 15 minutes',
		'*/20 * * * *'   => 'Every 20 minutes',
		'*/30 * * * *'   => 'Every 30 minutes',
		'30 * * * *'     => 'Every hour at 30 minutes',
		'0 * * * *'      => 'Every hour',
		'0 0 * * *'      => 'One per day',
		'0 0 * * 0'      => 'One per week',
		'0 0 1 * *'      => 'One per month',
		'0 0 1 1-12/3 *' => 'One per 2 months',
		'0 0 1 1 *'      => 'One per year',
	],
];