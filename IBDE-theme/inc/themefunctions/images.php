<?php
// ***********************************************************************//
// Images
// ***********************************************************************//

// Images: Add Featured Images Support
add_theme_support('post-thumbnails');

// Images: Add custom featured image sizes
if (function_exists('add_image_size')) {
    add_image_size('Square', 1170, 1170, true);
    add_image_size('SmallSquare', 600, 600, false);
    add_image_size('Landscape', 1170, 878, false);
    add_image_size('Portrait', 1170, 878, true);
    add_image_size('Panoramic Landscape', 1170, 658, true);
} 

// Images: Add custom selectable sizes in the WYSIWYG
add_filter('image_size_names_choose', 'my_image_sizes');
function my_image_sizes($sizes) {
    $addsizes = array(
        "Square" => __("Square"), 
        "Landscape" => __("Landscape"),
        "Portait" => __("Portait"),  
        "Panoramic Landscape" => __("Panoramic Landscape")
        );
    $newsizes = array_merge($sizes, $addsizes);
    return $newsizes;
}

// Images: Add custom classes to the author avatar
add_filter('get_avatar', 'add_gravatar_class');

function add_gravatar_class($class) {
    $class = str_replace("class='avatar", "class='avatar img-responsive", $class);
    return $class;
}

// Images: Add custom classes to images that are added via WYSIWYG
function give_linked_images_class($html, $id, $caption, $title, $align, $url, $size, $alt = '') {
    $classes = 'img-responsive';
    if (preg_match('/<img.*? class=".*?">/', $html)) {
        $html = preg_replace('/(<img.*? class=".*?)(".*?>)/', '$1 ' . $classes . '$2', $html);
    } else {
        $html = preg_replace('/(<img.*?)>/', '$1 class="' . $classes . '" >', $html);
    }
    return $html;
}
add_filter('image_send_to_editor', 'give_linked_images_class', 10, 8);
