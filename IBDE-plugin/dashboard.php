<?php 
/**
 * WordPress Dashboard Configurations
 */

/** 
 * Remove Dashboard Clutter
 */
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');
function remove_dashboard_widgets() {
  global $wp_meta_boxes;
  
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
  unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
  unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);    
}

/** 
 * Add forecast api and uptime robot widgets
 */
add_action('wp_dashboard_setup', 'ibde_dashboard_widgets');
function ibde_dashboard_widgets() {

  $user = wp_get_current_user();
  if ( in_array( 'administrator', (array) $user->roles ) ) {

   add_meta_box('ibde_forecast_widget', 'Forecast API', 'ibde_forecast_widget', 'dashboard', 'side', 'high');
   add_meta_box('ibde_uptime_widget', 'Uptime Robot', 'ibde_uptimerobot_widget', 'dashboard', 'side', 'high');
 }
}

/** 
 * Render forecast.io widget
 */
function ibde_forecast_widget() {
  $api_calls = get_transient( 'forecast_API_calls' );

  if ( ! $api_calls ) {
    echo '<p>Couldn\'t load API data... </p>';
  }

  $value = $api_calls['calls'];
  $last_updated = $api_calls['last_updated'];

  $double_value = (double) $value;
  $percent = ($double_value / 1000.00) * 100.00;

  echo "<p>Used {$value} of 1000 free calls. Last fetched: {$last_updated}.</p>";
  echo '<img src="//chart.googleapis.com/chart?cht=gom&chs=600x300&chd=t:' . $percent . '&chco=5cb85c,72C25E,88CB5F,F0F965,f0ad4e,d9534f" style="width:100%">';
  echo '<a target="_blank" class="button button-secondary button-small" href="https://developer.forecast.io/">See more</a>';
  
  echo '<hr>';
  $cron = get_transient('weather_cron_data');
  $cr_run = $cron['last_run'];

  global $wpdb;
  $number_of_expired = $wpdb->get_var( $wpdb->prepare( 
    "
    SELECT count(*)
    FROM wp_postmeta 
    WHERE post_id in (
      SELECT post_id 
      FROM wp_postmeta 
      WHERE meta_key = 'weather_cache_expire' 
        AND meta_value < UNIX_TIMESTAMP()
        ) 
    and meta_key = 'weather_view_count'
    AND meta_value > 0
    "
    ));
  
  echo "<p>Cron last run: {$cr_run}. {$number_of_expired} expired weather records.</p>";

}

/** 
 * Render uptime robot widget
 */
function ibde_uptimerobot_widget() {

  global $_KEYS;
	$api_key = $_KEYS['uptime_robot']['ibd-events.com'];

	?>
	<script type="text/javascript"
  src="https://www.google.com/jsapi?autoload={
    'modules':[{
      'name':'visualization',
      'version':'1',
      'packages':['line']
    }]
  }"></script>

  <div id="curve_chart"></div>
  <div id="uptime-images"></div>

  <script> 
   function jsonUptimeRobotApi (data) {

    var monitor = data.monitors.monitor[0];

    if (monitor.hasOwnProperty('responsetime')) {

      var api_data = [['Time', 'Response time']];
      for (var i = monitor.responsetime.length - 1; i >= 0; i--) {
        var time = monitor.responsetime[i]

        api_data.push([new Date(time.datetime), Number(time.value)]);
      }

      var data = google.visualization.arrayToDataTable(api_data);
      var options = google.charts.Line.convertOptions({
       height: 400, 
       curveType: 'function',
       hAxes: { gridlines: {
            units: {
              days: {format: ['MMM dd']},
              hours: {format: ['HH:mm', 'ha']},
            }
          },
          minorGridlines: {
            units: {
              hours: {format: ['hh:mm:ss a', 'ha']},
              minutes: {format: ['HH:mm a Z', ':mm']}
            }
          }},

       legend: { position: 'none' }
     });

     var chart = new google.charts.Line(document.getElementById('curve_chart'));
     chart.draw(data, options);
   }
 }
</script>

<script src="https://api.uptimerobot.com/getMonitors?apiKey=<?php echo $api_key; ?>&responseTimes=1&responseTimesAverage=30&customUptimeRatio=1-7-30-365&format=json" async></script> 
<?php

}
