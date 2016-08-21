  <header class="wrap header">
    <div class="container header">
      <div class="row">
        <div class="col-sm-4">
          <h1><a href="<?php echo site_url(); ?>">IBD Events</a></h1>
        </div>
        <div class="col-sm-4">
          <strong>The directory of Crohn's and Colitis events.</strong>
        </div>
        <div class="col-sm-4">
            <?php get_search_form();?>
        </div>
      </div>
    </div>
  </header>

  <nav class="wrap navigation">
    <div class="container navigation">
      <!-- NAVBAR ================================================== -->
      <?php if ( has_nav_menu( 'primary_menu' ) ) : ?>
        <div class="navbar navbar-default" role="navigation">
         <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="collapse navbar-collapse">
         <?php
         wp_nav_menu( array(
          'menu'              => 'primary_menu',
          'theme_location'    => 'primary_menu',
          'depth'             => 2,
          'container'         => 'ul',
          'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
          'menu_class'        => 'nav navbar-nav navbar-left',
          'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
          'walker'            => new wp_bootstrap_navwalker())
         );
         ?> 
       </div>
     </div>
   <?php endif; ?>
   <!--  ============================================== -->  
 </div>
</nav>
<div class="wrap central">
  <div class="container central">
