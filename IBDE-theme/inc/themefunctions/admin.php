<?php 
// ***********************************************************************//
// Admininistration / Dashboard modifications
// ***********************************************************************//

// Admin: Remove Admin Bar Items
function remove_admin_bar_links() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    $wp_admin_bar->remove_menu('comments');
}
add_action('wp_before_admin_bar_render', 'remove_admin_bar_links');
