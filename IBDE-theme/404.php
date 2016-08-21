<?php get_header();?>
<div class="row">

	<div class="primary col-sm-6">
	<div class="page-header">
			<h1>404: File Not Found</h1>
		</div>
	<p>Unfortunately, the page you requested could not be found. Sorry about that.</p>
	<p>A collection of pages you might be interested in has been provided for you.</p>
	<img src="https://ibd-events.com/wp-content/uploads/2016/08/catcher.jpg" class="img-responsive space-below">
	</div>
	<div class="primary col-sm-6">
	<h2>Searching for potentially relevant pages...</h2>
		<?php

		$url = $_SERVER['REQUEST_URI'];
		 //$parts = str_replace(["-", ".", "/", "_", "?", "="], " ", $url);
		$parts = preg_replace("/[^ \w]+/", " ", $url);

		 // WP_Query arguments
		$args = array (
			'post_type'              => get_post_types(),
			's'                      => $parts,
			'meta_query', array( array(
				'key'   => 'start_date_utc',
				'compare' => '>=',
				'value'   => date('Y-m-d H:i'),
			)),
			'posts_per_page' => 2
			);

		 // The Query
		$the_query = new WP_Query( $args );

		if ( $the_query->have_posts() ) {

	while ( $the_query->have_posts()) { 
	$the_query->the_post(); 
	?>
			<div class="row hr">	
				<?php if ( '' != get_the_post_thumbnail() ) : ?>	
					<!-- =============================================== -->
					<div class="col-xs-4">
						<a href="<?php the_permalink();?>"><?php the_post_thumbnail('SmallSquare', array('class' => 'img-responsive')) ;?></a>
					</div>
					<div class="col-xs-8">
						<h3 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
						<?php the_excerpt(); ?>
					</div>
					<!-- =============================================== -->
					<!-- Without Thumbnail -->
				<?php else : ?>
					<!-- =============================================== -->
					<div class="col-sm-12">
						<h3 class="flushtop"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
						<?php the_excerpt(); ?>
					</div>
					<!-- =============================================== -->
				<?php endif; ?>
			</div>
		<?php } // end of the loop. 


} else {
	echo "<p>No relevant pages could be found. Sorry again!</p>";
}


		?>
	</div>
</div>
<?php get_footer(); ?>