<?php
// ***********************************************************************//
// Widgets
// ***********************************************************************//

if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name'=> 'Sidebar Widgets',
        'id' => 'sidebar_widget',
        'before_widget' => '<div class="hr">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));
}