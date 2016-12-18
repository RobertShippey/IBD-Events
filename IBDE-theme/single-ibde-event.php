<?php get_header();?>

<?php 
global $post; 
$event = new IBDEvent($post->ID);
?>

<div class="row">
	<div class="col-sm-10">
		<div class="page-header">
			<h1><?php the_title();?> <small><?php the_field('tagline'); ?></small></h1>
		</div>
	</div>
</div>

<div class="row">
	<div class="primary col-sm-8">

		<div class="row">

			<?php 

			$start_date_obj = ibde_get_start_date();
			$end_date_obj = ibde_get_end_date();

			$now_obj = new DateTime();

			if ($end_date_obj) {
				if ($end_date_obj < $now_obj) {
					$notice = "This event has ended.";
				} elseif ($start_date_obj < $now_obj) {
					$notice = "This event has started.";
				}
			} else {
				$fudge_end_date = ibde_get_start_date();
				$fudge_end_date->add(new DateInterval("PT2H"));

				if ($fudge_end_date < $now_obj) {
					$notice = "This event has ended.";
				} elseif ($start_date_obj < $now_obj) {
					$notice = "This event has started.";
				}
			} 
			if (isset($notice)) {
				echo '<div class="col-xs-12">';
				echo "<div class=\"alert alert-info\" role=\"alert\">{$notice}</div>";
				echo '</div>';
			}
			?>
			

			<?php if ( '' !== get_the_post_thumbnail() ) { ?>			
				<div class="col-md-7">
					<?php } else { ?>
						<div class="col-md-12">
							<?php } ?>

							<?php while ( have_posts() ) : the_post();

							if ('' !== $post->post_content) { ?>
								<blockquote class="content"><?php the_content(); ?></blockquote>
								<?php } endwhile; ?>


							<?php 
							if ('' !== get_field('base_price_amount')) { ?>
								<div class="row">
									<div class="col-sm-10"> 
										<?php } ?>

								<h3 id="event-time" title="Local time"><?php echo $event->formatted_start_date('jS F h:i A'); ?></h3>

								<?php if ($event->has_end_date()) { ?>
									<script type="text/javascript">
										var startTime = moment(<?php echo wp_json_encode($event->formatted_start_date('Y-m-d\TH:i:s')); ?>, moment.ISO_8601);
										var endTime = moment(<?php echo wp_json_encode($event->formatted_end_date('Y-m-d\TH:i:s')); ?>, moment.ISO_8601);

										var timeRange = moment.twix(startTime, endTime);

										var eventTime = document.getElementById('event-time');
										eventTime.innerHTML = timeRange.format({ 
											monthFormat: "MMMM",
											dayFormat: "Do",
											hideYear: true
										});

									</script>
									<?php } ?>

									<?php $venue = get_field('venue'); 
									if ($venue) { ?>
										<h3><?php echo $venue; ?></h3>
										<?php } ?>

										<?php $location = get_field('location'); 
										if ($location) { ?>
											<address>
												<?php 	
												$country_code = get_post_meta( $post->ID, 'country_code', true );
												if ( ! empty( $country_code ) ) {
													echo sprintf('<img class="small-flag" src="%s/img/flags/%s.png" height="64" width="64" alt="%s">', get_template_directory_uri(), $country_code, $country_code);
												} ?>
												<?php echo $location['address']; ?>
											</address>
											<?php } ?>

											<?php  
											if ('' !== get_field('base_price_amount')) { ?>

											</div><div class="col-sm-2"> <?php 
											$currency_code = get_field( 'base_price_currency' );
											$base_price = get_field( 'base_price_amount' );

											if ( "0" === $base_price ) {
												echo "Tickets are <strong>FREE</strong>";
											} elseif ($base_price) {

												$formatter = new NumberFormatter( 'en', NumberFormatter::CURRENCY );
												echo 'Tickets from <h4>' . $formatter->formatCurrency($base_price, $currency_code) . ' <small>'.$currency_code.'</small></h4>';
											}
											?> </div> </div> 
											<?php }  ?>
												</div>


										<?php if ( '' !== get_the_post_thumbnail() ) : ?>
											<div class="col-md-5">
												<?php the_post_thumbnail( 'Landscape', array( 'class' => 'img-responsive space-below' ) ); ?>
											</div>
										<?php endif; ?>

											</div>
										</div>


										<div class="col-sm-4">

									<?php  
									$terms = get_the_terms( $post->ID, 'ibde-category' );
									if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
										echo '<div class="taxonomy-list space-below"><h3>Event categories</h3>';
										foreach ( $terms as $term ) {
											echo '<a href="' . get_term_link( $term->term_id, 'ibde-category' ) 
											. '" title="' . sprintf('View all %s events', $term->name ) 
											. '" class="btn btn-secondary">' 
											. $term->name . '</a> ';
										}
										echo '</div>';
									} ?>

									<?php  
									$location_terms = wp_get_object_terms( $post->ID,'ibde-location', array('orderby' => 'term_group', 'order' => 'DESC', 'fields' => 'all'));
									if ( ! empty( $location_terms ) && ! is_wp_error( $location_terms ) ) {
										$location_terms_rev = array_reverse($location_terms);
										echo '<div class="taxonomy-list"><h3>Location</h3>';
										echo '<p class="button-list">';
										foreach ( $location_terms_rev as $term ) {
											echo '<a href="' . get_term_link( $term->slug, 'ibde-location' ) . '" title="' . sprintf('View all %s events', $term->name ) . '" class="btn btn-secondary btn-sm">' . $term->name . '</a> ';
										}
										echo '</p>';
										echo '</div>';
									} ?>

									<p><a href="<?php the_permalink(); echo "ical/"; ?>" class="btn btn-primary btn-sm" download><i class="fa fa-calendar-plus-o"></i> Add to calendar</a></p>

									<?php if (get_field('external_link')) {
										$external_url = get_field('external_link');
										$external_hostname = wp_parse_url($external_url, PHP_URL_HOST); 
										$external_sitename = str_replace("www.", "", $external_hostname); ?>

										<div id="external-link">
											<div class="favico-box">
												<img class="favico fav-blur" height="16" width="16" src="https://www.google.com/s2/favicons?domain=<?php echo $external_hostname; ?>" alt="<?php echo $external_sitename; ?> favicon">
												<img class="favico fav-small" height="16" width="16" src="https://www.google.com/s2/favicons?domain=<?php echo $external_hostname; ?>" alt="<?php echo $external_sitename; ?> favicon">
											</div>
											<p>Event details from<br><a href="<?php echo $external_url; ?>" target="_blank"><?php echo $external_sitename; ?></a></p>
										</div>
										<?php } ?>

										<?php if (get_field('buy_tickets')) {
											$buy_url = get_field('buy_tickets');
											$buy_hostname = wp_parse_url($buy_url, PHP_URL_HOST); 
											$buy_sitename = str_replace("www.", "", $buy_hostname); 

											if (get_field('tickets_action_verb')) {
												$action_text = get_field('tickets_action_verb') . ' ';
											} else {
												$action_text = 'Buy tickets from ';
											}
											?>

											<p><img class="favico" height="16" width="16" src="https://www.google.com/s2/favicons?domain=<?php echo $buy_hostname; ?>" alt="<?php echo $buy_sitename; ?> favicon"> 
												<?php echo $action_text; ?><a href="<?php echo $buy_url; ?>" target="_blank"><?php echo $buy_sitename; ?></a>
											</p>
											<?php } ?>

											<?php if (get_field('hashtag')) { ?>
												<pre><a href="https://twitter.com/hashtag/<?php the_field('hashtag'); ?>" target="_blank">#<?php the_field('hashtag'); ?></a></pre>
												<?php } ?>

												<?php

												$weather_data = ibde_get_weather($post->ID);
						if (isset($weather_data['summary']) && null !== $weather_data['summary'] ) { // only show weather if there is a 'summary'
						?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Weather</h3>
							</div>
							<div class="panel-body">
								<canvas id="weatherIcon" class="pull-right" width="100" height="100"></canvas>
								<h3><?php echo $weather_data['summary']; ?></h3>
								<p><?php

									if ( null !== $weather_data['precipIntensity'] && round($weather_data['precipIntensity'], 1) > 0) {
										if ( null !== $weather_data['precipProbability'] ) {
											echo "There is a " . ($weather_data['precipProbability'] * 100) . "% chance it will " . $weather_data['precipType'] . " about " . round($weather_data['precipIntensity'], 1) . " inches. ";
										} else {
											echo "It could " . $weather_data['precipType'] . " about " . $weather_data['precipIntensity'] . " inches. ";
										}
									} 
									if (round($weather_data['temperature']) == round($weather_data['apparentTemperature'])) {
										echo "Temperature will be " . round($weather_data['temperature']) . "ºC. ";
									} else {
										echo "Temperature will be " . round($weather_data['temperature']) . "ºC, but feel like " . round($weather_data['apparentTemperature']) . "ºC. ";
									}
									if ($weather_data['windSpeed'] > 0) {
										$direction = cardinal_direction($weather_data['windBearing']);
										echo "Wind will be " . round($weather_data['windSpeed']) . "mph from the " . strtolower($direction['full_name']) . ". ";
									}
									?></p>
								</div>
							</div>

							<script>
								var skycons = new Skycons({"monochrome": false});
								skycons.add("weatherIcon", <?php echo wp_json_encode($weather_data['icon']); ?>);
								skycons.play();
							</script>
							
							<?php  }  ?>

							<?php if (get_field('online_event')) {
								$zones = array(
									"Eastern Time" => "America/New_York",
									"Pacific Time" => "America/Los_Angeles",
									"Sydney" => "Australia/Sydney",
									"London" => "Europe/London",
									);
								$start_date = ibde_get_start_date();
								?>
								<div class="panel panel-default">
									<div class="panel-heading">
									<h3 class="panel-title">Timezones</h3>
									</div>

									 <ul class="list-group">
									<?php foreach ($zones as $key => $value) {
									  $start_date->setTimezone(new DateTimeZone($value));
									  echo '<li class="list-group-item">' . $key . ': ' . $start_date->format('h:i A ') . '<small>' . $start_date->format('jS F') . '</small></li>';
									} ?>
									</ul>
									
								</div>
								<?php 
							} ?>


							<div class="row social-share">
								<div class="col-xs-4 col-sm-12 col-md-4 col-xl-4">
									<div class="share-button">
										<a href="https://twitter.com/share" class="twitter-share-button" data-via="IBD_Events">Tweet</a>
										<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if (!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
									</div>
								</div>
								<div class="col-xs-4 col-sm-12 col-md-4 col-xl-4">
									<div class="share-button">
										<iframe src="https://www.facebook.com/plugins/share_button.php?href=<?php echo rawurlencode(get_permalink()); ?>&amp;layout=button" style="height: inherit;"></iframe>
									</div>
								</div>
								<div class="col-xs-4 col-sm-12 col-md-4 col-xl-4">
									<div class="share-button">
										<div class="g-plus" data-action="share" data-annotation="none"></div>
										<script type="text/javascript">
											(function() {
												var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
												po.src = 'https://apis.google.com/js/platform.js';
												var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
											})();
										</script>
									</div>
								</div>
							</div>

							<p>Event posted by <?php the_author_posts_link(); ?>.</p>

						</div>
					</div>

				</div>
			</div>


			<?php $location = get_field('location'); 
			if ($location) { ?>
				<div class="wrap">
					<div id="single-map" class="space-below"></div> 
				</div>
				<?php } ?>

				<?php 	
				$lat = (double) $location["lat"];
				$lng = (double) $location["lng"];

				$pos = array( 
					'lat' => $lat, 
					'lng' => $lng,
					); ?>
				<script>
					function initMap() {
						var latlng = <?php echo wp_json_encode($pos); ?>;
						var map = new google.maps.Map(document.getElementById('single-map'), {
							scrollwheel: false,
							zoom: 15,
							center: latlng
						});

						var infowindow = new google.maps.InfoWindow({
							content: <?php echo wp_json_encode(get_the_title()); ?>
						});
						var marker = new google.maps.Marker({
							position: latlng,
							map: map,
							title: <?php echo wp_json_encode(get_the_title()); ?>
						});
						marker.addListener('click', function() {
							infowindow.open(marker.get('map'), marker);
						});
						infowindow.open(map,marker);
					}
				</script>
				<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9F5PQBKaFAVgfkSKtCyM99pylUrHigcU&signed_in=true&callback=initMap"></script>

				<div class="wrap ">
					<div class="container ">

						<div class="col-sm-12 sm-text-right">
							<p><a class="btn btn-primary btn-sm" href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-exclamation-triangle"></i> Report an issue with this event</a></p>
						</div>

						<!-- Modal -->
						<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Report: <?php the_title(); ?></h4>
									</div>
									<div class="modal-body">
										<?php if ( function_exists( 'ninja_forms_display_form' ) ) { 
											ninja_forms_display_form( 5 ); 
											} ?>
									</div>
								</div>
							</div>
						</div>	

						<?php 
						if ( ! empty( $location_terms ) && ! is_wp_error( $location_terms ) ) {

							$bread1 = array();
							$bread1['@context'] = "http://schema.org";
							$bread1['@type'] = "BreadcrumbList";

							$crumbs1 = array();
							$position = 1;

							$crumbs1[] = array(
								"@type" => "ListItem",
								"position" => $position++,
								"item" => array(
									"@id" => "https://www.ibd-events.com/",
									"name" => "IBD Events",
									),
								);

							foreach ( $location_terms as $term ) {

								$crumbs1[] = array(
									"@type" => "ListItem",
									"position" => $position++,
									"item" => array(
										"@id" => get_term_link( $term->slug, 'ibde-location' ),
										"name" => $term->name,
										),
									);
							}

							$crumbs1[] = array(
								"@type" => "ListItem",
								"position" => $position++,
								"item" => array(
									"@id" => get_permalink(),
									"name" => get_the_title(),
									),
								);

							$bread1['itemListElement'] = $crumbs1;

							echo '<script type="application/ld+json">';
							echo wp_json_encode($bread1, JSON_PRETTY_PRINT); 
							echo '</script>';
						}
						?>
						<?php 

						$schema = array();
						$schema['@context'] = "http://schema.org";
						$schema['@type'] = "Event";
						$schema['name'] = get_the_title();

						$location_schema = array();
						$location_schema['@type'] = "Place";
						@$location_schema['address'] = array(
							"@type" => "PostalAddress", 
							"name" => $location['address'], 
							"addressCountry" => $country_code,
							);
						$location_schema['geo'] = array(
							"@type" => "GeoCoordinates", 
							"latitude" => $location["lat"], 
							"longitude" => $location["lng"],
							);
						$location_schema['name'] = get_field('venue');
						$schema['location'] = $location_schema;

						$schema['url'] = get_permalink();
						$schema['startDate'] = $event->formatted_start_date('Y-m-d\TH:i:sP');
						$schema['endDate'] = $event->formatted_end_date('Y-m-d\TH:i:sP');
						$schema['sameAs'] = get_field('external_link');

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

						?>

						<script type="application/ld+json" id="IBD-Schema">
							<?php echo wp_json_encode($schema, JSON_PRETTY_PRINT); ?>
						</script>

						<?php get_footer(); ?>
