<?php

class IBDEvent
{

  private $post_id;
  private $start_date;
  private $end_date;
  private $location;

  function __construct($post_id) {
   $this->post_id = $post_id;

   $this->start_date = new DateTime();
   $this->start_date->setTimestamp((int)get_field('start_date', $this->post_id));

   if (get_field('end_date')) {
     $this->end_date = new DateTime();
     $this->end_date->setTimestamp((int)get_field('end_date', $this->post_id));
   } else {
    $this->end_date = null;
  }

  $this->location = get_field('location', $this->post_id);

}


public function formatted_start_date($format) {
  $start_date = ibde_get_start_date();
  return $start_date->format($format);
}

public function formatted_end_date($format) {

  if ( get_field('end_date' )) { 
    $end_date = ibde_get_end_date();
    return $end_date->format($format);
  } else {
    return null;
  }
}

public function has_end_date() {
  if ( get_field('end_date' )) { 
    return true;
  } else {
    return false;
  }
}

public function formatted_end_date_or_default($format) {

  $end_date_formatted = formatted_end_date($format);

  if ( null === $end_date_formatted ) {
   $start_date = ibde_get_start_date();
   $start_date->add(new DateInterval("PT2H"));
   $end_date_formatted = $start_date->format('Ymd\THis');

 }

 return $end_date_formatted;
}

public function coords () {
  return array('lat' => $this->location['lat'], 'lng' => $this->location['lng']);
}

public function has_weather () {
  // if has location, date and time, and is not online then true
  if ( $this->location ) {
    return true;
  } else {
    return false;
  }
}

}
