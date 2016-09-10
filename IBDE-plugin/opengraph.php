<?php

function ibde_opengraph_type ( $type ) {
    if ( 'ibde-event' == get_post_type() ) {
        return "ibdevents:event";
    } else {
        return $type;
    }
}
add_filter( 'wpseo_opengraph_type', 'ibde_opengraph_type' );

add_action( 'wp_head', function() {
    global $post;
    // make sure it's a WooCommerce product on display
    if ( 'ibde-event' === get_post_type() ) {

        $start_date = ibde_get_start_date();
        $start_date_string = $start_date->format('Y-m-d\TH:i:s');

        echo '<meta property="ibdevents:start_time" content="' . $start_date_string . '" />';

        if ( get_field('end_date')) {
            $end_date = ibde_get_end_date();
            $end_date_string = $end_date->format('Y-m-d\TH:i:s');

            echo '<meta property="ibdevents:end_time" content="' . $end_date_string . '" />';
        }

        if ( get_field('location')) {
            $location = get_field('location');
            
            echo '<meta property="ibdevents:location:latitude" content="' . $location['lat'] . '" />';
            echo '<meta property="ibdevents:location:longitude" content="' . $location['lng'] . '" />';
        }
    }
} );



function ibde_opengraph_meta_desc ( $text ) {
    global $post;

    if (is_singular('ibde-event') ) {

   $start_date = ibde_get_start_date();

   $date_string = $start_date->format('jS M');

        $text = $date_string . '. ' . $text;
    }

        $text = strip_shortcodes($text);
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
        

        $allowed_tags = '';
        $text = strip_tags($text, $allowed_tags);


         $text = ibde_trim_excerpt($text, 20, true);

     $text = trim($text);

    return $text ;
}
add_filter( 'wpseo_metadesc', 'ibde_opengraph_meta_desc' );


function ibde_meta_title ( $title ) {

    if ( is_singular('ibde-event') ){

         $post_title = get_the_title();

   $start_date = ibde_get_start_date();
   $date = $start_date->format('jS M');

       $site = get_bloginfo( 'name' );

       $title = sprintf("%s - %s - %s", $post_title, $date, $site);

    }
    return $title;
}
add_filter( 'wpseo_title', 'ibde_meta_title', 1, 2 );

/** Trim Excerpt function */
function ibde_trim_excerpt ($text, $length, $finish_sentence) {
    // Word length of the excerpt. This is exact or NOT depending on your '$finish_sentence' variable.
    $length = 15;
     /* Change the Length of the excerpt as you wish. The Length is in words. */
    
    // 1 if you want to finish the sentence of the excerpt (No weird cuts).
    $finish_sentence = 1;
     // Put 0 if you do NOT want to finish the sentence.
    
    $tokens = array();
    $out = '';
    $word = 0;
    
    // Divide the string into tokens; HTML tags, or words, followed by any whitespace.
    $regex = '/(<[^>]+>|[^<>\s]+)\s*/u';
    preg_match_all($regex, $text, $tokens);
    foreach ($tokens[0] as $t) {
        
        // Parse each token
        if ($word >= $length && !$finish_sentence) {
            
            // Limit reached
            break;
        }
        if ($t[0] != '<') {
            
            // Token is not a tag.
            // Regular expression that checks for the end of the sentence: '.', '?' or '!'
            $regex1 = '/[\?\.\!]\s*$/uS';
            if ($word >= $length && $finish_sentence && preg_match($regex1, $t) == 1) {
                
                // Limit reached, continue until ? . or ! occur to reach the end of the sentence.
                $out.= trim($t);
                break;
            }
            $word++;
        }
        
        // Append what's left of the token.
        $out.= $t;
    }

    return $out;
}
