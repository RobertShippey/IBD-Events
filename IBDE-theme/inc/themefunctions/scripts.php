<?php
// ***********************************************************************//
// Scripts
// ***********************************************************************//

// Add function to enqueue extra scripts
add_action('wp_enqueue_scripts', 'lingo_load_javascript_files');

// Register the scripts
function lingo_load_javascript_files() {
    wp_register_script('Bootstrap', get_template_directory_uri() . '/bower_components/bootstrap/dist/js/bootstrap.min.js', array('jquery'), true);
    wp_register_script('eqHeight', get_template_directory_uri() . '/js/jquery.eqheight.js', array('jquery'), true);
    
    // Get the scripts ready for use
    wp_enqueue_script('Bootstrap');
    wp_enqueue_script('eqHeight');

    wp_register_script('moment', get_template_directory_uri() . '/js/moment.min.js', array(), true);
    wp_register_script('twix', get_template_directory_uri() . '/js/twix.min.js', array('moment'), true);

    wp_register_script('skycons', get_template_directory_uri() . '/js/skycons.min.js', array('jquery'), true);


    // Get the scripts ready for use
    wp_enqueue_script('Bootstrap');
    wp_enqueue_script('eqHeight');

    wp_enqueue_script('moment');
    wp_enqueue_script('twix');

    wp_enqueue_script('skycons');
}