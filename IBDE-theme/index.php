<?php get_header();?>

<div class="row">
	<div class="col-sm-12">
		<div class="page-header">
			<h1>News <small>about IBD Events</small></h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="primary col-sm-8 space-below-2x">
		<?php if ( '' != term_description() ) : ?>
			<?php echo term_description(); ?>
			<hr />
		<?php endif; ?>
		
		<!-- =============================================== -->
		<?php while ( have_posts() ) : the_post(); ?>
			<div class="row hr">
			<div class="col-xs-12">
				<h2 class="flushtop"><?php the_title();?></h2>
				</div>
				<?php if ( '' != get_the_post_thumbnail() ) : ?>	
					<!-- =============================================== -->
					<div class="col-xs-4">
						<?php the_post_thumbnail('Square', array('class' => 'img-responsive')) ;?>
					</div>
					<div class="col-xs-8">
						<?php the_content(); ?>
					</div>
					<!-- =============================================== -->
					<!-- Without Thumbnail -->
				<?php else : ?>
					<!-- =============================================== -->
					<div class="col-sm-12">
						<?php the_content(); ?>
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

<div class="col-sm-4">
<h3>Global Crohn's and Colitis News</h3>
<p>Recent news stories about Crohn's Disease and Ulcerative Colitis from around the world, sourced from <a href="https://news.google.co.uk/" target="_blank">Google News</a>, not affiliated with IBD Events.</p>
<?php echo do_shortcode('[IBDNews]'); ?>
</div>
</div>

<?php get_footer(); ?>