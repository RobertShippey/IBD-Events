<?php 
/**
 * Reading and writing event start/end dates to db with TZ conversion
 */

/**
 * Run the `update` functions when a post is saved.
 *
 * @param int $post_id post id being updated.
 */
function ibde_format_dates_for_db ($post_id) {

  if (get_post_type($post_id) === "ibde-event") {

   ibde_update_start_date($post_id);
   ibde_update_end_date($post_id);
 }
}
add_action('acf/save_post', 'ibde_format_dates_for_db', 40); 


/**
 * Get start date.
 *
 * Gets the start date for the current $post.
 *
 * @return date formatted start date.
 */
function ibde_get_start_date () {

  $start_date_string = get_field('start_date_utc');
  $start_date_obj = new DateTime($start_date_string, new DateTimeZone("UTC") );

  $start_date_tz_string = get_field('timezone');
  $output_tz_obj = new DateTimeZone($start_date_tz_string);

  $start_date_obj->setTimezone( $output_tz_obj );

  return $start_date_obj;
}

/**
 * Save start date.
 *
 * Takes the start date stored in local time and saves UTC version to database.
 *
 * @param int $post_id the post to update.
 */
function ibde_update_start_date ($post_id) {

  $start_date_tz_string = get_field('timezone', $post_id);
  $output_tz_obj = new DateTimeZone($start_date_tz_string);

  $start_date_string = get_field('start_date', $post_id);
  $start_date_obj = new DateTime($start_date_string, $output_tz_obj );

  $start_date_obj->setTimezone( new DateTimeZone("UTC") );

  $save_start_date_str = $start_date_obj->format('Y-m-d H:i');

  update_field('start_date_utc', $save_start_date_str, $post_id);
}



/**
 * Get end date.
 *
 * Gets the end date for the current $post.
 *
 * @return date formatted end date.
 */
function ibde_get_end_date () {

  $end_date_string = get_field('end_date_utc');

  if ($end_date_string) {
    $end_date_obj = new DateTime($end_date_string, new DateTimeZone("UTC") );

    $end_date_tz_string = get_field('timezone');
    $output_tz_obj = new DateTimeZone($end_date_tz_string);

    $end_date_obj->setTimezone( $output_tz_obj );

    return $end_date_obj;
  }
}

/**
 * Save end date.
 *
 * Takes the end date stored in local time and saves UTC version to database.
 *
 * @param int $post_id the post to update.
 */
function ibde_update_end_date ($post_id) {

  $end_date_tz_string = get_field('timezone', $post_id);
  $output_tz_obj = new DateTimeZone($end_date_tz_string);

  $end_date_string = get_field('end_date', $post_id);

  if ($end_date_string) {
    $end_date_obj = new DateTime($end_date_string, $output_tz_obj );
    $end_date_obj->setTimezone( new DateTimeZone("UTC") );

    $save_end_date_string = $end_date_obj->format('Y-m-d H:i');

    update_field('end_date_utc', $save_end_date_string, $post_id);
  }
}
