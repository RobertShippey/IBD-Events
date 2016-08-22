<?php 


if( !wp_next_scheduled( 'ibde_update_taxonomy_count_cron' ) ) {
	// Schedule the event
	wp_schedule_event( time(), 'daily', 'ibde_update_taxonomy_count_cron' );
}
add_action( 'ibde_update_taxonomy_count_cron', 'ibde_update_tax_count' );


function ibde_update_tax_count () {

	$query = "UPDATE wp_term_taxonomy LEFT JOIN (
        SELECT wp_term_taxonomy.term_id,
    Count(id) AS `new_count`
FROM wp_term_relationships,
    wp_posts,
    wp_postmeta,
    wp_term_taxonomy
WHERE (
        taxonomy = 'ibde-location'
        OR taxonomy = 'ibde-category'
        )
    AND wp_term_taxonomy.`term_taxonomy_id` = wp_term_relationships.
    `term_taxonomy_id`
    AND wp_term_relationships.`object_id` = wp_posts.`id`
    AND post_status = 'publish'
    AND wp_postmeta.`post_id` = wp_posts.`id`
    AND wp_postmeta.`meta_key` = 'start_date_utc'
    AND wp_postmeta.`meta_value` > UTC_TIMESTAMP()
GROUP BY wp_term_relationships.term_taxonomy_id    
        ) AS T2 ON wp_term_taxonomy.`term_id` = T2.term_id  SET `count` = IFNULL(T2.`new_count`, 0)";

	global $wpdb;
	$wpdb->query($query);
}
