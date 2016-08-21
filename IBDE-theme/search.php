<?php get_header();?> 
<!-- =============================================== -->
<?php if ( have_posts() ) : ?>
	<div class="row">
		<div class="col-sm-12">
			<div class="page-header">
				<h1>Search</h1>
				<p class="lead">A search for <strong><?php the_search_query(); ?></strong> 
					found <strong><?php echo $wp_query->found_posts; ?></strong> event<?php echo ($wp_query->found_posts > 1) ? "s" : ""; ?>.</p>
				</div>
			</div>
		</div>

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
			<?php endwhile; ?>
		<?php else : ?>
			<div class="page-header">
				<h1>No results</h1>
			</div>
			<h3>Sorry, we couldn't find anything to do with <?php the_search_query(); ?>.</h3>
			<h4>Try searching for something else here:</h4>
			<?php get_search_form();?>
		<?php endif; ?>
		<!-- =============================================== -->
	</div>
	<?php get_footer(); ?>