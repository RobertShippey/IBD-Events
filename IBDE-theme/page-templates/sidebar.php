 <?php
 /*
Template Name: Sidebar page
*/

get_header(); ?>

<h1><?php the_title(); ?></h1>

<div class="row">

  <div class="col-sm-8">

  <?php the_content(); ?>
   
   </div>

    <div class="col-sm-4">

    <?php the_field('sidebar_content'); ?>

  </div>
</div>

<?php get_footer(); ?>