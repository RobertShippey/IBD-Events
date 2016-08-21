<?php 


function ibde_add_ical_endpoint() {
    add_rewrite_endpoint( 'ical', EP_PERMALINK | EP_SEARCH | EP_CATEGORIES | EP_AUTHORS );
}
add_action( 'init', 'ibde_add_ical_endpoint' );


function ibde_ical_template_redirect() {
    global $wp_query;

    if (! isset( $wp_query->query_vars['ical'] )) {
     return;
 }

 ical_thing();
 exit;
}
add_action( 'template_redirect', 'ibde_ical_template_redirect' );

function ical_thing() {

    ob_start();

    echo "BEGIN:VCALENDAR" . "\r\n";
    echo "VERSION:2.0" . "\r\n";
    echo "PRODID:-//IBDEvents.com v1.0//EN" . "\r\n";

    while ( have_posts() ) { 
        the_post(); 
        global $post; 
        echo "BEGIN:VEVENT" . "\r\n";
        echo "DTSTAMP:" . gmdate('Ymd\THis\Z', time()) . "\r\n"; 
        if (get_field('start_date_utc')) { 
            $start_date = ibde_get_start_date();
            $start_date_string = $start_date->format('Ymd\THis');
            echo "DTSTART:" . $start_date_string . "\r\n";
        } 

        if (get_field('end_date_utc')) { 
            $end_date = ibde_get_end_date();
            $end_date_string = $end_date->format('Ymd\THis');
            echo "DTEND:" . $end_date_string . "\r\n";
        } else { 
            $start_date->add(new DateInterval("PT2H"));
            $fudge_end_date = $start_date->format('Ymd\THis');
            echo "DTEND:" . $fudge_end_date . "\r\n";
        } 

        echo "SUMMARY:" . escapeString(get_the_title()) . "\r\n"; 

        $location = get_field('location'); 
        if($location) { 
            echo "LOCATION:" . escapeString($location['address']) . "\r\n"; 
            //echo "GEO:" . $location['lat'] . "," . $location['lng'] . "\r\n"; 
        } 

        echo "UID:" . escapeString(wp_get_shortlink()) . "\r\n";  
        echo "SEQUENCE:0" . "\r\n";

        $content = apply_filters( 'the_content', get_the_content() );
        $content = str_replace( ']]>', ']]&gt;', $content );
        $content = strip_tags($content);
        $content = trim($content);
        $content = str_replace(array("\n"), "\\n", $content);
        $content = $content." ".get_permalink();
        $content = escapeString($content); 
        echo "DESCRIPTION:" .  $content . "\r\n"; 

        $terms = get_the_terms( $post->ID, 'ibde-category' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $CATS = array();
            foreach ( $terms as $term ) {
             $CATS[] = strtoupper($term->name);
         } 

         if(count($CATS) > 0) { 
            echo "CATEGORIES:" . implode(", ", $CATS) . "\r\n"; 
        }  
    }
    echo "URL:" . escapeString(get_permalink()) . "\r\n";
    echo "TRANSP:OPAQUE" . "\r\n";
    echo "END:VEVENT" . "\r\n";

}

echo "END:VCALENDAR";


$content = ob_get_contents();
$length = strlen($content);
ob_end_clean();

header('Content-type: text/calendar');
header('Content-Disposition: attachment; filename=ibd-events.ics');
header('Content-Length: '. $length);
header('Cache-Control: max-age=3600'); // cache for 1 hour

echo $content;

}


function dateToCal($timestamp) {
  return date('Ymd\THis\Z', $timestamp);
}
// Escapes a string of characters
function escapeString($string) {
    $string = html_entity_decode($string, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
    return preg_replace('/([\,;])/','\\\$1', $string);
}
