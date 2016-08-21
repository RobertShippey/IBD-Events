<?php get_header();?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-header">
		<h1><?php single_term_title(); ?> <small><?php echo strip_tags(term_description()); ?></small></h1>
		</div>
	</div>
</div>


<div class="row">
	<div class="primary col-sm-9">
		
		<!-- =============================================== -->
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
				

				<?php 
					$schema = array();
					$schema['@context'] = "http://schema.org";
					$schema['@type'] = "Event";
					$schema['name'] = get_the_title();

					$locationSchema = array();
					$locationSchema['@type'] = "Place";
					@$locationSchema['address'] = array(
						"@type"=> "PostalAddress", 
						"name"=> $location['address'], 
						"addressCountry"=> $country_code);
					$locationSchema['geo'] = array(
						"@type"=>"GeoCoordinates", 
						"latitude"=>$location["lat"], 
						"longitude"=>$location["lng"]);
					$locationSchema['name'] = get_field('venue');
					$schema['location'] = $locationSchema;

					$schema['url'] = get_permalink();
					$schema['startDate'] = $event->formatted_start_date('Y-m-d\TH:i:s');
					$schema['endDate'] = $event->formatted_end_date('Y-m-d\TH:i:s');
					$schema['sameAs'] = array(get_permalink(), get_field('external_link'));

					$schema['image'] = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$schema['image'] = $schema['image'][0];

				$schema['offers']['@type'] = "Offer";
				if (get_field('buy_tickets')) {
					$schema['offers']['url'] = get_field('buy_tickets');
				} else {
					$schema['offers']['url'] = get_field('external_link');
				}
				$schema['offers']['sameAs'] = $schema['offers']['url'];

				$content = apply_filters( 'the_content', get_the_content() );
				$content = str_replace( ']]>', ']]&gt;', $content );
				$content = strip_tags($content);
				$content = trim($content);

				$schema['description'] = $content;
?>
						<script type="application/ld+json">
							<?php echo json_encode($schema, JSON_PRETTY_PRINT); ?>
						</script>

			</div>
			
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

<div class="col-sm-3">
	<h3>More categories</h3>
		<?php 
		$taxonomy_name = 'ibde-category';

		$args = array(
			'taxonomy' => $taxonomy_name,
			'hide_empty' => 1,
			'hierarchical' => true,
			'depth'  => 1,
			'orderby' => 'count',
			'order' => 'desc',
			//'number' => 8
			);
		$cats = get_categories( $args );

		echo '<p class="button-list">';
		foreach ($cats as $place) {
			echo '<a href="' . get_term_link( $place->term_id, $taxonomy_name ) . '" class="btn btn-secondary btn-sm">'
			. $place->name
			. '</a> ';
		}
		echo "</p>";
		?>
</div>

</div>

<?php get_footer(); ?>