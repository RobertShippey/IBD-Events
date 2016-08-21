<?php
// ***********************************************************************//
// Menus
// ***********************************************************************//

// Add extra menus
function register_my_menus() {
    register_nav_menus(array(
        'primary_menu' => 'Primary Menu',
        'sidebar_menu' => 'Sidebar Menu',
        'footer_menu' => 'Footer Menu',
        'baseline_menu' => 'Baseline Menu'
        ));
}
add_action('init', 'register_my_menus');