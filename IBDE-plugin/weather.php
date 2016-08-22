<?php 

function ibde_get_weather ($postID) {

  update_weather_view_counter($postID);

  $weatherData = get_post_meta( $postID, 'weather_data', true);

  if ( false === $weatherData) {
   $weatherData = refresh_weather_data($postID);
 }

 return $weatherData;
}

function refresh_weather_data ($postID) {

	$location = get_field('location', $postID);
	if(!$location) {
		return false;
	}  

  $start_date = ibde_get_start_date();
  $weatherDate = $start_date->format('Y-m-d\TH:i:s');

  global $_KEYS;

  $forecastFormat = "https://api.forecast.io/forecast/".$_KEYS['forecast.io']."/%s,%s,%s?units=uk2";
  $forecaseURL = sprintf($forecastFormat, $location['lat'], $location['lng'], $weatherDate);

  $weatherCH = curl_init();

  curl_setopt($weatherCH, CURLOPT_URL, $forecaseURL);
  curl_setopt($weatherCH, CURLOPT_RETURNTRANSFER, true); // return the output in string format 
  curl_setopt($weatherCH, CURLOPT_HEADERFUNCTION, "HandleHeaderLine"); 
  $weatherOutput = curl_exec($weatherCH); // execute 
  curl_close($weatherCH); // close curl handle

  $weatherResponse = json_decode($weatherOutput, true);

  if ($weatherResponse) {

    // pull out specific information into array
  	$weatherData = array();
  	@$weatherData['summary'] = $weatherResponse['currently']['summary'];
  	@$weatherData['icon'] = $weatherResponse['currently']['icon'];
    @$weatherData['precipType'] = $weatherResponse['currently']['precipType']; //If precipIntensity is zero, then this property will not be defined.
    @$weatherData['precipIntensity'] = $weatherResponse['currently']['precipIntensity'];
    @$weatherData['precipProbability'] = $weatherResponse['currently']['precipProbability'];
    @$weatherData['temperature'] = $weatherResponse['currently']['temperature'];
    @$weatherData['apparentTemperature'] = $weatherResponse['currently']['apparentTemperature'];
    @$weatherData['windSpeed'] = $weatherResponse['currently']['windSpeed'];
     @$weatherData['windBearing'] = $weatherResponse['currently']['windBearing']; // wind coming FROM in degrees
     @$weatherData['cloudCover'] = $weatherResponse['currently']['cloudCover'];
     
     // not used in front end - might want... maybe
     @$weatherData['sources'] = $weatherResponse['flags']['sources'];

     // cache weather for twice as long as suggested by Forecast.io
     global $weatherMaxAge;
     $cacheLength = $weatherMaxAge * 2;

    // if the doubled cache length is less than 2 hrs
     if (7200 > $cacheLength) {
       $cacheLength = 7200; // set the cache length to 2 hrs
     }

     $weatherData['cacheLength'] = $cacheLength;

     update_post_meta($postID, 'weather_data', $weatherData);
     update_post_meta($postID, 'weather_cache_expire', time() + $cacheLength);

     //set_transient( $transientID, $weatherData);
     return $weatherData;
   }
 }


 function update_weather_view_counter ($postID) {
  $counterKey = 'weather_view_count';

  $view_count = get_post_meta( $postID, $counterKey , true );
  if( ! $view_count ) {
    update_post_meta ($postID, $counterKey, 1);
    $view_count = 1;
  } else {
    global $wpdb;
    $wpdb->query(
      $wpdb->prepare("
        UPDATE $wpdb->postmeta 
        SET meta_value = (meta_value + 1) 
        WHERE post_id = %d 
        AND meta_key = %s
        ", $postID, $counterKey));
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

  $ID = get_next_cache_reset();
  if (null !== $ID ) {

    global $post; 
    $post = get_post( $ID, OBJECT );
    setup_postdata( $post );

    refresh_weather_data($ID);
    update_post_meta ($ID, 'weather_view_count', 0);

    $cron_data['last_updated'] = date("F j, Y, g:i a");

    wp_reset_postdata();
  }

  $cron_data['last_ID'] = $ID;
  set_transient('weather_cron_data', $cron_data);
}


// pull the max age from Forecast IO header
function HandleHeaderLine( $curl, $header_line ) {
  // pull out cache expiry
	if (ibde_startsWith($header_line, "Cache-Control")) {
		$max_age_string = str_replace("Cache-Control: max-age=", "", $header_line);
		global $weatherMaxAge;
		$weatherMaxAge = (int)$max_age_string;
	}

  // update dashboard stats
	if (ibde_startsWith($header_line, "X-Forecast-API-Calls")) {

		$calls_used_string = str_replace("X-Forecast-API-Calls: ", "", $header_line);
    //$new_value = (int)$calls_used_string;

		$api_data = array(
			'calls' => $calls_used_string,
			'last_updated' => date("F j, Y, g:i a"));

		set_transient('forecast_API_calls', $api_data);
	}
	return strlen($header_line);
}


// helper function
function ibde_startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
}
