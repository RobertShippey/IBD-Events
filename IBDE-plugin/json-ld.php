<?php

/** 
 * Generates JSON+LD for a post
 */
function ibde_generate_jsonld ( ) {

	$schema = array();
	$schema['@context'] = "http://schema.org";
	$schema['@type'] = "Event";
	$schema['url'] = get_permalink() . '#event';

	$schema['name'] = get_the_title();

	$location_schema = array();
	$location_schema['@type'] = "Place";
	@$location_schema['address'] = array(
		"@type" => "PostalAddress", 
		"name" => $location['address'], 
		"addressCountry" => $country_code,
		);
	$location_schema['geo'] = array(
		"@type" =>"GeoCoordinates", 
		"latitude" => $location["lat"], 
		"longitude" => $location["lng"],
		);
	$location_schema['name'] = get_field('venue');
	$schema['location'] = $location_schema;

	$schema['url'] = get_permalink();
	
	$startDate = ibde_get_start_date();
	$schema['startDate'] = $startDate->format('Y-m-d\TH:i:s');
	$endDate = ibde_get_end_date();
	if ($endDate) {
		$schema['endDate'] = $endDate->format('Y-m-d\TH:i:s');
	}
	
	$schema['image'] = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
	$schema['image'] = $schema['image'][0];

	$schema['offers']['@type'] = "Offer";
	if (get_field('buy_tickets')) {
		$schema['offers']['url'] = get_field('buy_tickets');
	} else {
		$schema['offers']['url'] = get_field('external_link');
	}
	$schema['offers']['sameAs'] = $schema['offers']['url'];

	if (get_field('base_price_currency')) {
		$schema['offers']['priceCurrency'] = get_field('base_price_currency');
	}
	if (get_field('base_price_amount')) {
		$schema['offers']['@type'] = "AggregateOffer";
		$schema['offers']['lowPrice'] = get_field('base_price_amount');
	}

	$content = apply_filters( 'the_content', get_the_content() );
	$content = str_replace( ']]>', ']]&gt;', $content );
	$content = strip_tags($content);
	$content = trim($content);

	$schema['description'] = $content;

	$schema['sameAs'] = array(get_permalink(), get_field('external_link'));

	return $schema;
}
