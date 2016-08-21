<?php get_header();?>
<div class="row">
	<div class="col-sm-12">
		<div class="page-header">
			<h1><?php the_title();?></h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="primary col-sm-12">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	</div>
</div>
<?php get_footer(); ?>