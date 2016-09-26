<?php

function output_ibd_news(){

	$output = "";

// Get any existing copy of our transient data
	if ( false === ( $feed = get_transient( 'ibd_news_feed_data' ) ) ) {
    // It wasn't there, so regenerate the data and save the transient
		$feed = file_get_contents("https://www.google.co.uk/alerts/feeds/15264550752376347014/13590176083876989495");
		if (! $feed) {
			return "Sorry, the news could not be loaded, please try again later.";
		}
		$feed = str_replace(array("&lt;b&gt;","&lt;/b&gt;"), "", $feed);

		set_transient( 'ibd_news_feed_data', $feed, 4 * HOUR_IN_SECONDS );
	}

	$newses = new SimpleXMLElement($feed);
	$count = 0;

	foreach ($newses->entry as $news) {

		$stop_words = array("marijuana", "cannabinoid", "cannabis", "cialis");
		foreach ($stop_words as $word) {
			$content = strtolower($news->title . " " . $news->content);
			
			if (strpos($content, $word) !== false) {
				continue 2;
			}
		}

		$count++;
		if ($count > 5) {
			break;
		}

		$link = '<a href="'.$news->link['href'].'" rel="nofollow">';
		$source = str_replace("https://www.google.com/url?rct=j&sa=t&url=", "", $news->link['href']);
		$source_host = parse_url($source, PHP_URL_HOST); 	
		$hostname = str_replace("www.", "", $source_host);

		if (!empty($news->author->name)) {
			$byline = $news->author->name;
		}

		$ago = ibd_news_get_relative_time($news->published);

		$output .= "<hr>";
		$output .= "<h4>$link" . $news->title . "</a></h4>";
		$output .= "<p><img src=\"https://www.google.com/s2/favicons?domain={$hostname}\" alt=\"{$hostname}\" style=\"height:1em;\"> ";
		$output .= "<small>Posted $ago on $link $hostname</a> $byline</small></p>"; 
		$output .= "<p>" . $news->content . "</p>";

	}
	return $output;
}
add_shortcode( 'IBDNews', 'output_ibd_news' );


function ibd_news_get_relative_time($datetime, $depth=1) {

	$units = array(
		"year" => 31104000,
		"month" => 2592000,
		"week" => 604800,
		"day" => 86400,
		"hour" => 3600,
		"minute" => 60,
		"second" => 1
		);

	$plural = "s";
	$conjugator = " and ";
	$separator = ", ";
	$suffix1 = " ago";
	$suffix2 = " left";
	$now = "now";
	$empty = "";

    // DO NOT EDIT BELOW

	$timediff = time()-strtotime($datetime);
	if ($timediff == 0) return $now;
	if ($depth < 1) return $empty;

	$max_depth = count($units);
	$remainder = abs($timediff);
	$output = "";
	$count_depth = 0;
	$fix_depth = true;

	foreach ($units as $unit => $value) {
		if ($remainder > $value && $depth-->0) {
			if ($fix_depth) {
				$max_depth -= ++$count_depth;
				if ($depth >= $max_depth) $depth = $max_depth;
				$fix_depth = false;
			}
			$u = (int)($remainder / $value);
			$remainder %= $value;
			$pluralise = $u > 1 ?$plural:$empty;
			$separate = $remainder == 0 || $depth == 0 ? $empty:
			($depth == 1 ? $conjugator : $separator);
			$output .= "{$u} {$unit}{$pluralise}{$separate}";
		}
		$count_depth++;
	}
	return $output.($timediff<0?$suffix2:$suffix1);
}

