<?php get_header();?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-header">
			<h1><?php single_term_title(); ?></h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="primary col-sm-12">
		<?php if ( '' != term_description() ) : ?>
			<?php echo term_description(); ?>
			<hr />
		<?php endif; ?>
		
		<!-- =============================================== -->
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="row hr">	
				<?php if ( '' != get_the_post_thumbnail() ) : ?>	
					<!-- =============================================== -->
					<div class="col-xs-4">
						<a href="<?php the_permalink();?>"><?php the_post_thumbnail('Square', array('class' => 'img-responsive')) ;?></a>
					</div>
					<div class="col-xs-8">
						<h2 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
						<?php the_excerpt(); ?>
					</div>
					<!-- =============================================== -->
					<!-- Without Thumbnail -->
				<?php else : ?>
					<!-- =============================================== -->
					<div class="col-sm-12">
						<h2 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
						<?php the_excerpt(); ?>
					</div>
					<!-- =============================================== -->
				<?php endif; ?>
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
</div>

<?php get_footer(); ?>