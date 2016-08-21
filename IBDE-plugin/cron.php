<?php 

// Add custom cron interval
add_filter( 'cron_schedules', 'ibde_cron_schedules', 10, 1 );

function ibde_cron_schedules( $schedules ) {
	// $schedules stores all recurrence schedules within WordPress
	$schedules['two_minutes'] = array(
		'interval'	=> 120,	// Number of seconds, 120 in 2 minutes. Do not go below 86.4 seconds.
		'display'	=> 'Once Every 2 Minutes'
	);

	// Return our newly added schedule to be merged into the others
	return (array)$schedules; 
}


if( !wp_next_scheduled( 'refresh_ibde_weather' ) ) {
	// Schedule the event
	wp_schedule_event( time(), 'two_minutes', 'refresh_ibde_weather' );
}

add_action( 'refresh_ibde_weather', 'refresh_expired_weather_cron' );