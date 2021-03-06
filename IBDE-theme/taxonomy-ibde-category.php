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

	<?php  if (have_posts()) { ?>	
		<!-- =============================================== -->
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
						<a href="<?php the_permalink();?>"><?php the_post_thumbnail('SmallSquare', array ('class' => 'img-responsive')) ;?></a>
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


	<?php } else { ?>
			<h2>No Events Found</h2>
			<p>Sorry, we don't have any upcoming <?php single_term_title(); ?> events at the moment, check out the other categories instead.</p>
			<p>If you know if any, please <a href="/submission">let us know</a>.</p>

			<?php
			$args = array(
				'post_type'              => 'ibde-event',
				'posts_per_page'         => '3',
				'no_found_rows'			=> true,
				);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {

				echo '<hr>';
				echo '<h3>Upcoming Events Around the World</h3>';
				echo '<div class="row">';
				while ( $query->have_posts() ) {
					$query->the_post(); ?>
					<div class="col-xs-12 col-sm-6 col-md-4 eq space-below" style="height: 200px;">
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
			);
		$cats = get_categories( $args );

		echo '<p class="button-list">';
		foreach ($cats as $place) {
			echo '<a href="' . get_term_link( $place->term_id, $taxonomy_name ) . '" class="btn btn-secondary btn-sm">'
			. $place->name
			. '</a> ';
		}
		echo '</p>';
		?>
</div>

</div>

<?php get_footer(); ?>