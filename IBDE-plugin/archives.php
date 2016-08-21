<?php


// Author archive lists events not posts
// function ibde_author_events( &$query )
// {
//     if ( $query->is_author ) {
//         $query->set( 'post_type', 'ibde-event' );
//       }
//     remove_action( 'pre_get_posts', 'ibde_author_events' ); // run once!
// }
// add_action( 'pre_get_posts', 'ibde_author_events' );


// Order archives by `start_date` 
function ibde_custom_sort_order( $query ) {


	// do not modify queries in the admin
	if( is_admin() || is_single() ) {
		return;	
	}

	if( isset( $query->query_vars['ical']) ) {
		$query->set('posts_per_page', -1);
	}

	if ( $query->is_author ) {
		$query->set( 'post_type', 'ibde-event' );
	}

	if ( isset($query->query_vars['ibde-location']) ||  isset($query->query_vars['ibde-category'])) {
		$query->set( 'post_type', 'ibde-event' );
	}
	
	// only modify queries for 'event' post type
	if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'ibde-event' ) {

		if ( ! isset($query->query_vars['orderby'])) {
			
			$query->set('orderby', 'meta_value');	
			$query->set('meta_key', 'start_date_utc');	 
			$query->set('order', 'ASC'); 
		} 

		$query->set('meta_query', array( array(
			'key'   => 'start_date_utc',
			'compare' => '>=',
			'value'   => date('Y-m-d H:i'),
//			'type' => 'NUMERIC'
			)));
	}
	
	//remove_action( 'pre_get_posts', 'ibde_custom_sort_order' ); // run once!
}

add_action('pre_get_posts', 'ibde_custom_sort_order');
