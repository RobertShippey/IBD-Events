<?php get_header();?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-header">
			<h1><?php single_term_title(); ?> <small><?php echo strip_tags(term_description()); ?></small></h1>
		</div>

		<?php  if (have_posts()) { 
			$taxonomy_name = 'ibde-location';
			$location_id = $wp_query->get_queried_object_id();

			$args = array(
				'child_of' => $location_id,
				'parent' => $location_id,
				'taxonomy' => $taxonomy_name,
				'hide_empty' => 1,
				'hierarchical' => true,
				'depth'  => 1,
				'orderby' => 'count',
				'order' => 'desc',
				'number' => 10,
				);
			$cats = get_categories( $args );

			echo '<p class="button-list">';
			foreach ($cats as $place) {
				echo '<a href="' . get_term_link( $place->term_id, $taxonomy_name ) . '" class="btn btn-secondary btn-sm">'
				. $place->name
				. '</a> ';
			}
			echo '</p>';

		} else { ?>
			<h2>No Events Found</h2>
			<p>Sorry, we don't have any upcoming events in <?php single_term_title(); ?> at the moment.</p>
			<p>If you know if any, please <a href="/submission">let us know</a>.</p>

			<?php
			$args = array(
				'post_type'              => 'ibde-event',
				'posts_per_page'         => '4',
				'no_found_rows'			=> true,
				);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {

				echo '<hr>';
				echo '<h3>Upcoming Events Around the World</h3>';
				echo '<div class="row">';
				while ( $query->have_posts() ) {
					$query->the_post(); ?>
					<div class="col-xs-12 col-sm-4 col-md-3 eq space-below" style="height: 200px;">
						<div class="featured-event-block">
							<div class="row">

								<div class="col-xs-12">
									<h4 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
								</div>
								<?php if ( '' !== get_the_post_thumbnail() ) : ?>

								<div class="col-xs-12">
									<a href="<?php the_permalink();?>">
										<?php the_post_thumbnail('SmallSquare', array( 'class' => 'pull-right featured-event-image' )) ;?>
									</a>
								<?php else : ?>

								<div class="col-xs-12">
								<?php endif; ?>

								<?php $start_date = ibde_get_start_date(); ?>
								<p><?php echo $start_date->format('jS F'); ?></p>

								<p>
									<?php $venue = get_field('venue'); 
									if ( $venue ) { ?>
										<strong><?php echo $venue; ?></strong>
										<br>
										<?php } ?>

										<?php 
										$terms = wp_get_object_terms(get_the_ID(), 'ibde-location', array( 'orderby' => 'term_group', 
											'order' => 'ASC', 
											'fields' => 'all',
											));

										if ( ! empty( $terms ) ) {
											if ( ! is_wp_error( $terms ) ) {

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
						</div>
						<?php }
						echo '</div>';
					} 
					wp_reset_postdata();
					?>
					<?php } ?>

	</div>
</div>
</div>
</div>

<?php  if (have_posts()) { ?>
<div class="wrap central">
<!-- <div class="row">
	<div class="col-sm-12"> -->
		<div id="archive-map" class="space-below"></div> 
	<!-- </div>
</div> -->
</div>

<div class="wrap central">
	<div class="container central">

		<div class="row">
			<div class="primary col-sm-12">
				
				<!-- =============================================== -->
				<?php $pins = array(); ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php $event = new IBDEvent(get_the_ID()); ?>
					<div class="row hr">	
						<!-- =============================================== -->

						<?php if ( '' !== get_the_post_thumbnail() ) : ?>

							<div class="col-xs-8 col-sm-8">
								<h2 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
								<p class="h3 flushtop"><?php echo $event->formatted_start_date('jS F'); ?></p>
								<p><?php $location = get_field('location'); 
								if ( $location ) { 
									echo $location['address']; 
									} ?></p>
								<?php the_excerpt(); ?>
							</div>

							<div class="col-xs-4 col-sm-3 col-sm-offset-1">
								<a href="<?php the_permalink();?>"><?php the_post_thumbnail('SmallSquare', array( 'class' => 'img-responsive' ));?></a>
							</div>
						<?php else : ?>

							<div class="col-xs-12 col-sm-8">
								<h2 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
								<p class="h3 flushtop"><?php echo $event->formatted_start_date('jS F'); ?></p>
								<p><?php $location = get_field('location'); 
								if ( $location ) { 
									echo $location['address']; 
								} ?></p>
								<?php the_excerpt(); ?>
							</div>

						<?php endif; ?>
				<script type="application/ld+json">
					<?php 
					$schema = ibde_generate_jsonld();
					echo wp_json_encode($schema, JSON_PRETTY_PRINT);
					?>
				</script>
			</div>

					<?php $location = get_field('location'); 
					if ( $location ) { 

						$lat = (double) $location["lat"];
						$lng = (double) $location["lng"];

						$pos = array( 
							'lat' => $lat, 
							'lng' => $lng,
							);


						$pins[] = array(
							'pos' => $pos,
							'title' => get_the_title(),
							'link' => get_permalink(),
							);
						} ?>

					<?php endwhile; // end of the loop. ?>
					<!-- =============================================== -->
					<?php global $wp_query; if ( $wp_query->max_num_pages > 1 ) : ?>
					<div class="row">
						<div class="col-sm-12">
							<hr />
							<ul class="pager">
								<li><?php previous_posts_link( 'Previous' ); ?></li>
								<li><?php next_posts_link( 'Next' ); ?></li>
							</ul>
						</div>
					</div>
				<?php endif; ?>
			</div>

		</div>

		<script>
			function initMap() {
				var map = new google.maps.Map(document.getElementById('archive-map'), {
					scrollwheel: false,
					zoom: 16,
					center: {lat:0,lng:0}
				});
				var markerBounds = new google.maps.LatLngBounds();

				<?php foreach ($pins as  $pin) {
					$content = sprintf('<a href="%s">%s</a>', $pin['link'], $pin['title']);
					?> 

					var latLgn = <?php echo wp_json_encode($pin['pos']); ?>;
					//markerBounds.extend(latLgn);

					var marker = new google.maps.Marker({
						position: latLgn,
						map: map,
						title: <?php echo wp_json_encode($pin['title']); ?>
					});

					marker.info = new google.maps.InfoWindow({
						content: <?php echo wp_json_encode($content); ?>
					});


					google.maps.event.addListener(marker, 'click', function() {
						this.info.open(map, this);
					});
					markerBounds.extend(marker.getPosition());
					marker.info.open(map,marker);
					<?php } ?>

					google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
						map.setZoom(map.getZoom()-2);

						if (this.getZoom() > 15) {
							this.setZoom(15);
						}
					});

					map.fitBounds(markerBounds);
				}	
			</script>
			<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB9F5PQBKaFAVgfkSKtCyM99pylUrHigcU&signed_in=true&callback=initMap"></script>

	<?php } ?>

<?php get_footer(); ?>