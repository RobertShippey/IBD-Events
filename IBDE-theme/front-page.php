<?php get_header(); ?>

<?php 
// 'meta_key' => '_thumbnail_id' // use this in WP_Query for thumbnails
function displayEvent() {
	$eventID = get_the_ID(); ?>
	<div class="col-xs-12 col-sm-4 col-md-3 eq space-below" style="height: 200px;">
		<div class="featured-event-block">
			<div class="row">

				<div class="col-xs-12">
					<h4 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
				</div>
				<?php if ( '' != get_the_post_thumbnail() ) : ?>

					<div class="col-xs-12">
						<a href="<?php the_permalink();?>">
							<?php the_post_thumbnail('SmallSquare', array('class' => 'pull-right featured-event-image')) ;?>
						</a>
					<?php else : ?>

						<div class="col-xs-12">
						<?php endif; ?>

						<?php $start_date = ibde_get_start_date(); ?>
						<p><?php echo $start_date->format('jS F'); ?></p>

						<p>
							<?php $venue = get_field('venue'); 
							if($venue) { ?>
							<strong><?php echo $venue; ?></strong>
							<br>
							<?php } ?>

							<?php 
							$terms = wp_get_object_terms(get_the_ID(), 'ibde-location', array('orderby' => 'term_group', 'order' => 'ASC', 'fields' => 'all'));

							if ( ! empty( $terms ) ) {
								if ( ! is_wp_error( $terms ) ) {

		//if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
									$terms = array_reverse($terms);
									foreach ( $terms as $term ) {
										echo '<a href="' . get_term_link( $term->slug, 'ibde-location' ) . '" title="' . sprintf('View all %s events', $term->name ) . '" class="btn btn-secondary btn-xs">' . $term->name . '</a> ';
									}
								} 
							} ?></p>
						</div>

						<div class="col-xs-12">
							<a class="btn btn-primary btn-sm" href="<?php the_permalink();?>"> More details »</a>
						</div>

					</div>
				</div>

				<?php 

				$location = get_field('location');

				$schema = array();
				$schema['@context'] = "http://schema.org";
				$schema['@type'] = "Event";
				$schema['name'] = get_the_title();

				$locationSchema = array();
				$locationSchema['@type'] = "Place";
				@$locationSchema['address'] = array(
					"@type"=> "PostalAddress", 
					"addressCountry"=> get_post_meta( $eventID, 'country_code', true));
				if($location) {
					$locationSchema['address']['name'] = $location['address'];
					$locationSchema['geo'] = array(
						"@type"=>"GeoCoordinates", 
						"latitude"=>$location["lat"], 
						"longitude"=>$location["lng"]);
				}
				$locationSchema['name'] = get_field('venue');
				$schema['location'] = $locationSchema;

				$schema['url'] = get_permalink();
				$schema['startDate'] = $start_date->format('Y-m-d\TH:i:s');
				if ($end_stamp = get_field('end_date', $eventID)) {
					$start_date = new DateTime();
					$start_date->setTimestamp((int)$end_stamp);
					$schema['endDate'] = $start_date->format('Y-m-d\TH:i:s');
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
				?>
				<script type="application/ld+json">
					<?php echo json_encode($schema, JSON_PRETTY_PRINT); ?>
				</script>

			</div>
			<?php } 
// END OF displayEvent() function  ~~~~~~~~
			?>

			<div class="row space-below-2x">
				<div class="primary col-sm-12">
					<h2>Upcoming Events</h2>
					<p>These Crohn's and Colitis events are starting soon.</p>

					<div class="row">
						<?php

// WP_Query arguments
						$args = array (
							'post_type'              => 'ibde-event',
							'posts_per_page'         => '4',
							'no_found_rows'			=> true
							);

// The Query
						$query = new WP_Query( $args );

// The Loop
						if ( $query->have_posts() ) {

							while ( $query->have_posts() ) {
								$query->the_post();

								displayEvent(); 
							}
						} else {
	// no posts found
						}

// Restore original Post Data
						wp_reset_postdata();

						?>
					</div>

				</div>
			</div>




			<div class="row space-below-2x">
				<div class="primary col-sm-12">

					<?php 

					$GeoIPTransient = 'geoip_' . $_SERVER['REMOTE_ADDR'];

					if ( false === ( $short_country_name = get_transient( $GeoIPTransient ) ) ) {
  // It wasn't there, so regenerate the data and save the transient
						$url = "http://ip-api.com/json/" . $_SERVER['REMOTE_ADDR'] . "?fields=country,status";
						$theBody = wp_remote_retrieve_body( wp_remote_get($url) );
						$values = json_decode($theBody);
						$short_country_name = $values->country;
						set_transient( $GeoIPTransient, $short_country_name, 30 * DAY_IN_SECONDS );
					} 
					?>

					<h2 class="visible-xs-inline visible-sm-inline visible-md-inline visible-lg-inline">
					Local Events</h2> 
					<span class="h2"><small><?php echo $short_country_name; ?></small></span>

					<?php
					$taxonomy_name = 'ibde-location';

					$country = get_term_by('name', $short_country_name, $taxonomy_name); 

					if ($country) {
						$args = array(
							'child_of' => $country->term_id,
							'parent' => $country->term_id,
							'taxonomy' => $taxonomy_name,
							'hide_empty' => 1,
							'hierarchical' => true,
							'depth'  => 1,
							'orderby' => 'count',
							'order' => 'desc',
							'number' => 10
							);
						$cats = get_categories( $args );

						echo "<p>Checkout something a little closer to home.<p>";
						echo '<p class="button-list">';
						foreach ($cats as $place) {
							echo '<a href="' . get_term_link( $place->term_id, $taxonomy_name ) . '" class="btn btn-secondary btn-sm">'
							. $place->name  
							. '</a> ';
						}
						echo "</p>"; ?>
						<div class="row">
							<?php

// WP_Query arguments
							$args = array (
								'post_type'              => 'ibde-event',
								'posts_per_page'         => '8',
								'no_found_rows'			 => true,
								'tax_query' => array(
									array(
										'taxonomy' => $taxonomy_name,
										'field'    => 'term_id',
										'terms'    => $country->term_id,
										),
									),
								);

// The Query
							$query = new WP_Query( $args );

// The Loop
							if ( $query->have_posts() ) {

								while ( $query->have_posts() ) {
									$query->the_post();

									displayEvent(); 
								}

								if ($query->post_count < 8) {
									?>
									<div class="col-xs-12 col-sm-4 col-md-3 eq space-below" style="height: 215px;">
<div class="featured-event-block">
<div class="row">
<div class="col-xs-12">
<h4 class="flushtop"><a href="/submission/">Your Event Here!</a></h4>
</div>
<div class="col-xs-12">
<p>Add events to the IBD Events directory for free.
</p>
<p>
<a href="<?php echo get_term_link( $country->term_id, $taxonomy_name ); ?>" class="btn btn-secondary btn-xs"><?php echo $country->name; ?></a></p>
</div>
<div class="col-xs-12">
<a class="btn btn-primary btn-sm" href="/submission/"> More details »</a>
</div>
</div>
</div>
</div> <?php 
								}
							} else {
								echo '<p>Unfortunately we don\'t have any events near you right now. '
								. '<a href="/about-us/">Let us know</a> and we\'ll try to find some!<p>';
							}

// Restore original Post Data
							wp_reset_postdata();

							?>
						</div>

						<?php 
					} else {
						echo '<p>Unfortunately we don\'t have any events near you right now. '
						. '<a href="/about-us/">Let us know</a> and we\'ll try to find some!<p>';
					}
					?>
				</div>
			</div>



			<div class="row space-below-2x">
				<div class="primary col-sm-12">

					<h2>Recently Added</h2>
					<p>Hot off the press, these are the new events we've just found out about.</p>

					<div class="row">
						<?php

						$args = array (
							'post_type'              => 'ibde-event',
							'posts_per_page'         => '4',
							'orderby'                => 'date',
							'no_found_rows' 		 => true
							);

						$query = new WP_Query( $args );

						if ( $query->have_posts() ) {

							while ( $query->have_posts() ) {
								$query->the_post();

								displayEvent(); 
							}
						} else {
							echo '<p>Strange, we didn\'t find any recently added events. Check back later.</p>';
						}
						wp_reset_postdata();

						?>
					</div>
				</div>
			</div>

			<?php get_footer(); ?>