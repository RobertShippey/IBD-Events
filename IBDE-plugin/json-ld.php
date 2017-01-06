<?php

/** 
 * Generates JSON+LD for a post
 */
function ibde_generate_jsonld () {

	global $post;

	$post_meta = get_post_meta($post->ID);

	$schema = array();
	$schema['@context'] = "http://schema.org";
	$schema['@type'] = "Event";
	$schema['url'] = get_permalink() . '#event';

	$schema['name'] = get_the_title();

	$country_code = $post_meta['country_code'][0];
	$location = unserialize($post_meta['location'][0]);
	
	$location_schema = array();
	$location_schema['@type'] = "Place";
	$location_schema['address'] = array(
		"@type" => "PostalAddress", 
		"name" => $location['address'], 
		"addressCountry" => $country_code,
		);
	$location_schema['geo'] = array(
		"@type" => "GeoCoordinates", 
		"latitude" => $location["lat"], 
		"longitude" => $location["lng"],
		);
	$location_schema['name'] = $post_meta['venue'][0];
	$schema['location'] = $location_schema;
	
	$start_date = ibde_get_start_date();
	$schema['startDate'] = $start_date->format('Y-m-d\TH:i:s');
	$end_date = ibde_get_end_date();
	if ($end_date) {
		$schema['endDate'] = $end_date->format('Y-m-d\TH:i:s');
	}
	
	$schema['image'] = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
	$schema['image'] = $schema['image'][0];

	$schema['offers']['@type'] = "Offer";
	if (get_field('buy_tickets')) {
		$schema['offers']['url'] = $post_meta['buy_tickets'][0];
	} else {
		$schema['offers']['url'] = $post_meta['external_link'][0];
	}
	$schema['offers']['sameAs'] = $schema['offers']['url'];

	if (get_field('base_price_currency')) {
		$schema['offers']['priceCurrency'] = $post_meta['base_price_currency'][0];
	}
	if (get_field('base_price_amount')) {
		$schema['offers']['@type'] = "AggregateOffer";
		$schema['offers']['lowPrice'] = $post_meta['base_price_amount'][0];
	}

	$content = apply_filters( 'the_content', get_the_content() );
	$content = str_replace( ']]>', ']]&gt;', $content );
	$content = strip_tags($content);
	$content = trim($content);

	$schema['description'] = $content;

	$schema['sameAs'] = array( get_permalink(), $post_meta['external_link'][0] );

	$no_of_performers = $post_meta['performers_repeater'][0];
	$performers = array();

	for ($i=0; $i < $no_of_performers; $i++) { 
		$performer = array(
			'@type' => 'Person',
			'name' => $post_meta["performers_repeater_{$i}_performer_name"][0],
			'sameAs' => $post_meta["performers_repeater_{$i}_performer_website"][0],
			'url' => $post_meta["performers_repeater_{$i}_performer_website"][0]
			);
		$performers[] = $performer;
	}
	$schema['performer'] = $performers;

	return $schema;
}
