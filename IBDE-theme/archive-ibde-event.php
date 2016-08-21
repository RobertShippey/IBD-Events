<?php get_header();?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-header">
		<h1><?php post_type_archive_title(); ?></h1>
		</div>
	</div>
</div>
</div>
</div>

<div class="wrap central">
<!-- <div class="row">
	<div class="col-sm-12"> -->
	<?php  if (have_posts()) { ?>
		<div id="archive-map" class="space-below"></div> 
	<?php } ?>
 	<!-- </div>
</div> -->
</div>

<div class="wrap central">
	<div class="container central">

		<div class="row">
			<div class="primary col-sm-12">
				<?php if ( '' != term_description() ) : ?>
					<?php echo term_description(); ?>
					<hr />
				<?php endif; ?>

				<!-- =============================================== -->
				<?php $pins = array(); ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php $event = new IBDEvent(get_the_ID()); ?>
					<div class="row hr">	
						<!-- =============================================== -->

						<?php if ( '' != get_the_post_thumbnail() ) : ?>

							<div class="col-xs-8 col-sm-8">
								<h2 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
								<p class="h3 flushtop"><?php echo $event->formatted_start_date('jS F'); ?></p>
								<p><?php $location = get_field('location'); if($location) { echo $location['address']; }?></p>
								<?php the_excerpt(); ?>
							</div>

							<div class="col-xs-4 col-sm-3 col-sm-offset-1">
								<a href="<?php the_permalink();?>"><?php the_post_thumbnail('SmallSquare', array('class' => 'img-responsive')) ;?></a>
							</div>
						<?php else : ?>

							<div class="col-xs-12 col-sm-8">
								<h2 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
								<p class="h3 flushtop"><?php echo $event->formatted_start_date('jS F'); ?></p>
								<p><?php $location = get_field('location'); if($location) { echo $location['address']; }?></p>
								<?php the_excerpt(); ?>
							</div>

						<?php endif; ?>

					</div>
					<?php $location = get_field('location'); 
					if($location) { 

						$lat = (double)$location["lat"];
						$lng = (double)$location["lng"];

						$pos = array( 
							'lat' => $lat, 
							'lng' => $lng
							);


						$pins[] = array(
							'pos' => $pos,
							'title' => get_the_title(),
							'link' => get_permalink()
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

					var latLgn = <?php echo json_encode($pin['pos']); ?>;
					//markerBounds.extend(latLgn);

					var marker = new google.maps.Marker({
						position: latLgn,
						map: map,
						title: <?php echo json_encode($pin['title']); ?>
					});

					marker.info = new google.maps.InfoWindow({
						content: <?php echo json_encode($content); ?>
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

			<?php get_footer(); ?>