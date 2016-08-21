<?php
// ***********************************************************************//
// Styles
// ***********************************************************************//

// Load the theme stylesheet from the CSS folder instead, datestamped to avoid refreshing cache
function lingo_styles() {
    wp_enqueue_style('main-stylesheet', get_template_directory_uri() . '/css/screen.css', null, filemtime( get_template_directory() . '/css/screen.css'));
}
add_action('wp_enqueue_scripts', 'lingo_styles');

// Make WYSIWYG use the same stylesheet
add_editor_style('css/screen.css');