<?php
/** 
 * Excerpts
 */

/** 
 * Let Pages have excerpts too
 */
add_action('init', 'my_add_excerpts_to_pages');
function my_add_excerpts_to_pages() {
    add_post_type_support('page', 'excerpt');
}

/** 
 * Clean Excerpt Generator
 */
function bac_variable_length_excerpt($text, $length, $finish_sentence) {
    
    // Word length of the excerpt. This is exact or NOT depending on your '$finish_sentence' variable. Change the Length of the excerpt as you wish. The Length is in words.
    $length = 20;
    
    // 1 if you want to finish the sentence of the excerpt (No weird cuts). Put 0 if you do NOT want to finish the sentence.
    $finish_sentence = 1;
    
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
                $out .= trim($t);
                break;
            }
            $word++;
        }
        
        // Append what's left of the token.
        $out .= $t;
    }
    
    // Add the excerpt ending as a link.
    $excerpt_end = '<br/> <br/> <a class="btn btn-primary btn-xs" href="' . get_permalink() . '"> More details &raquo;</a>';
    
    // Add the excerpt ending as a non-linked ellipsis with brackets.
    // $excerpt_end = ' [&hellip;]';
    
    // Append the excerpt ending to the token.
    $out .= $excerpt_end;
    
    return trim(force_balance_tags($out));
}

function bac_excerpt_filter($text) {
    
    global $post;
    if (empty($post->post_excerpt)) {
        
        // Get the full content and filter it.
        $text = get_the_content('');
        $text = strip_shortcodes($text);
        $text = apply_filters('the_content', $text);
        
        $text = str_replace(']]>', ']]&gt;', $text);
        
        // By default the code allows all HTML tags in the excerpt
        
        // Control what HTML tags to allow: If you want to allow ALL HTML tags in the excerpt, then do NOT touch.
        
        // If you want to Allow SOME tags: THEN Uncomment the next line + Line 80.
        // $allowed_tags = '<p>,<a>,<strong>'; // Here I am allowing p, a, strong tags. Separate tags by comma.
        
        // If you want to Disallow ALL HTML tags: THEN Uncomment the next line + Line 80,
        $allowed_tags = '';
        // To disallow all HTML tags, keep it empty. The Excerpt will be unformated but newlines are preserved. 
        $text = strip_tags($text, $allowed_tags);
         /* Line 80 */
        
        // Create the excerpt.
        $text = bac_variable_length_excerpt($text, 20, true);
        return $text;
    } else {
        return $text . '<a class="btn btn-primary btn-xs" href="' . get_permalink() . '"> More details &raquo;</a>';
    }
}

// Hooks the 'bac_excerpt_filter' function to a specific (get_the_excerpt) filter action.
add_filter('get_the_excerpt', 'bac_excerpt_filter', 5);
