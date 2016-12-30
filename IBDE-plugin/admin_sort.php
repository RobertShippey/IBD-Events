<?php
/**
 * Admin Sorting hooks
 */

/**
 * Adds Start Date column into Event admin list
 */
add_filter( 'manage_posts_columns', 'ibde_managing_my_event_columns', 10, 2 );
function ibde_managing_my_event_columns( $columns, $post_type ) {

  if ( $post_type == 'ibde-event' ){

    unset($columns['date']);

    $new_columns = array();
    foreach( $columns as $key => $value ) {
      $new_columns[ $key ] = $value;
      if ( $key == 'title' )
       $new_columns[ 'ibde_start_date_column' ] = 'Start Date';
   }
   return $new_columns;
 }
 return $columns;
}

/** 
 * Populates each row with the events start date
 */
add_action( 'manage_posts_custom_column', 'ibde_populating_my_event_columns', 10, 2 );
function ibde_populating_my_event_columns( $column_name, $post_id ) {

 switch( $column_name ) {
  case 'ibde_start_date_column':

  $start_date_string = get_post_meta( $post_id, 'start_date_utc', true );
  $start_date_obj = new DateTime($start_date_string, new DateTimeZone("UTC") );

  $start_date_tz_string = get_post_meta( $post_id, 'timezone', true );
  $output_tz_obj = new DateTimeZone($start_date_tz_string);

  if ( $start_date_obj && $output_tz_obj ) {
      $start_date_obj->setTimezone( $output_tz_obj );
      echo $start_date_obj->format('Y-m-d H:i');
  }

  break;
}
}

/** 
 * Registers Start Date column to be sortable
 */
add_filter( 'manage_edit-ibde-event_sortable_columns', 'ibde_manage_sortable_columns', PHP_INT_MAX );
function ibde_manage_sortable_columns( $sortable_columns ) {

 $sortable_columns[ 'ibde_start_date_column' ] = 'order_start_date';

 return $sortable_columns;
}

/** 
 * Sets the query to order by start date when set in admin
 */
add_action( 'pre_get_posts', 'ibde_event_admin_ordering_query', 99 );
function ibde_event_admin_ordering_query( $query ) {

 if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {

  switch( $orderby ) {

   case 'order_start_date':

   $query->set( 'meta_key', 'start_date_utc' );

   $query->set( 'orderby', 'meta_value' );

   break;

 }
}
}
