<form role="search" method="get" class="form" action="<?php echo home_url( '/' ); ?>">
  <div class="form-group">
    <input type="search" class="form-control" placeholder="Search..." value="<?php the_search_query(); ?>" name="s" title="Search for:" />
  </div>
  <input type="hidden" value="ibde-event" name="post_type" id="post_type" />
</form>