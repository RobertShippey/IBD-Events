<div class="hr">
<?php
wp_nav_menu( array(
	'menu'              => 'sidebar_menu',
	'theme_location'    => 'sidebar_menu',
	'depth'             => 1,
	'container'         => 'ul',
	'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
	'menu_class'        => 'nav nav-pills nav-stacked'
	));
?>
</div>

<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('Sidebar Widgets')) : ?>
<?php endif; ?>