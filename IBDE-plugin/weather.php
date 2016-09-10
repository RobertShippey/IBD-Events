<?php 

function ibde_get_weather ($post_id) {

  update_weather_view_counter($post_id);

  $weather_data = get_post_meta( $post_id, 'weather_data', true);

  if ( '' === $weather_data) {
   $weather_data = refresh_weather_data($post_id);
 }

 return $weather_data;
}

function refresh_weather_data ($post_id) {

	$location = get_field('location', $post_id);
	if ( !$location ) {
		return false;
	}  

  $start_date = ibde_get_start_date();
  $weatherDate = $start_date->format('Y-m-d\TH:i:s');

  global $_KEYS;

  $forecast_format = "https://api.forecast.io/forecast/".$_KEYS['forecast.io']."/%s,%s,%s?units=uk2";
  $forecase_URL = sprintf($forecast_format, $location['lat'], $location['lng'], $weatherDate);

  $weather_ch = curl_init();

  curl_setopt($weather_ch, CURLOPT_URL, $forecase_URL);
  curl_setopt($weather_ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format 
  curl_setopt($weather_ch, CURLOPT_HEADERFUNCTION, "handle_header_line"); 
  $weather_output = curl_exec($weather_ch); // execute 
  curl_close($weather_ch); // close curl handle

  $weather_response = json_decode($weather_output, true);

  if ($weather_response) {

    // pull out specific information into array
  	$weather_data = array();
  	@$weather_data['summary'] = $weather_response['currently']['summary'];
  	@$weather_data['icon'] = $weather_response['currently']['icon'];
    @$weather_data['precipType'] = $weather_response['currently']['precipType']; //If precipIntensity is zero, then this property will not be defined.
    @$weather_data['precipIntensity'] = $weather_response['currently']['precipIntensity'];
    @$weather_data['precipProbability'] = $weather_response['currently']['precipProbability'];
    @$weather_data['temperature'] = $weather_response['currently']['temperature'];
    @$weather_data['apparentTemperature'] = $weather_response['currently']['apparentTemperature'];
    @$weather_data['windSpeed'] = $weather_response['currently']['windSpeed'];
     @$weather_data['windBearing'] = $weather_response['currently']['windBearing']; // wind coming FROM in degrees
     @$weather_data['cloudCover'] = $weather_response['currently']['cloudCover'];
     
     // not used in front end - might want... maybe
     @$weather_data['sources'] = $weather_response['flags']['sources'];

     // cache weather for twice as long as suggested by Forecast.io
     global $weather_max_age;
     $cache_length = $weather_max_age * 2;

    // if the doubled cache length is less than 2 hrs
     if (7200 > $cache_length) {
       $cache_length = 7200; // set the cache length to 2 hrs
     }

     $weather_data['cacheLength'] = $cache_length;

     update_post_meta($post_id, 'weather_data', $weather_data);
     update_post_meta($post_id, 'weather_cache_expire', time() + $cache_length);

     return $weather_data;
   }
 }


 function update_weather_view_counter ($post_id) {
  $counter_key = 'weather_view_count';

  $view_count = get_post_meta( $post_id, $counter_key , true );
  if ( !$view_count ) {
    update_post_meta ($post_id, $counter_key, 1);
    $view_count = 1;
  } else {
    global $wpdb;
    $wpdb->query(
      $wpdb->prepare("
        UPDATE $wpdb->postmeta 
        SET meta_value = (meta_value + 1) 
        WHERE post_id = %d 
        AND meta_key = %s
        ", $post_id, $counter_key));
  }
}

function get_next_cache_reset () {
  global $wpdb;
  $most_viewed_expired = $wpdb->get_var( $wpdb->prepare( 
    "
    SELECT post_id 
    FROM $wpdb->postmeta 
    WHERE post_id in (
    SELECT post_id 
    FROM $wpdb->postmeta 
    WHERE meta_key = 'weather_cache_expire' 
    AND meta_value < UNIX_TIMESTAMP()
    ) 
    AND meta_key = %s 
    AND meta_value > 0
    ORDER BY meta_value+0 DESC 
    LIMIT 1;
    ", 
    'weather_view_count'
    ));
  return $most_viewed_expired;
}


function refresh_expired_weather_cron () {

  $cron_data['last_run'] = date("F j, Y, g:i a");

  $next_id = get_next_cache_reset();
  if (null !== $next_id ) {

    global $post; 
    $post = get_post( $next_id, OBJECT );
    setup_postdata( $post );

    refresh_weather_data($next_id);
    update_post_meta ($next_id, 'weather_view_count', 0);

    $cron_data['last_updated'] = date("F j, Y, g:i a");

    wp_reset_postdata();
  }

  $cron_data['last_ID'] = $next_id;
  set_transient('weather_cron_data', $cron_data);
}


/** pull the max age from Forecast IO header */
function handle_header_line( $curl, $header_line ) {
  // pull out cache expiry
	if (ibde_starts_with($header_line, "Cache-Control")) {
		$max_age_string = str_replace("Cache-Control: max-age=", "", $header_line);
		global $weather_max_age;
		$weather_max_age = (int)$max_age_string;
	}

  // update dashboard stats
	if (ibde_starts_with($header_line, "X-Forecast-API-Calls")) {

		$calls_used_string = str_replace("X-Forecast-API-Calls: ", "", $header_line);

		$api_data = array(
			'calls' => $calls_used_string,
			'last_updated' => date("F j, Y, g:i a"));

		set_transient('forecast_API_calls', $api_data);
	}
	return strlen($header_line);
}


/** helper function */
function ibde_starts_with($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}
