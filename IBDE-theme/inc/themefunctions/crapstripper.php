<?php
// ***********************************************************************//
// Crap Stripper (the NUCLEAR option)
// ***********************************************************************//

// Note: This filter works at the time of saving/updating the post.

add_filter( 'wp_insert_post_data' , 'filter_post_data' , '99', 2 );

function filter_post_data( $data , $postarr ) {
$content = $data['post_content'];
$content = preg_replace('#<p.*?>(.*?)</p>#i', '<p>\1</p>', $content);
$content = preg_replace('#<span.*?>(.*?)</span>#i', '<span>\1</span>', $content);
$content = preg_replace('#<ol.*?>(.*?)</ol>#i', '<ol>\1</ol>', $content);
$content = preg_replace('#<ul.*?>(.*?)</ul>#i', '<ul>\1</ul>', $content);
$content = preg_replace('#<li.*?>(.*?)</li>#i', '<li>\1</li>', $content);
$data['post_content'] = $content;
return $data;
}

// Note: This filter works at the time when function the_content() is executed.

add_filter( 'the_content', 'the_content_filter', 20 );

function the_content_filter( $content ) {
$content = preg_replace('#<p.*?>(.*?)</p>#i', '<p>\1</p>', $content);
$content = preg_replace('#<span.*?>(.*?)</span>#i', '<span>\1</span>', $content);
$content = preg_replace('#<ol.*?>(.*?)</ol>#i', '<ol>\1</ol>', $content);
$content = preg_replace('#<ul.*?>(.*?)</ul>#i', '<ul>\1</ul>', $content);
$content = preg_replace('#<li.*?>(.*?)</li>#i', '<li>\1</li>', $content);
return $content;
}