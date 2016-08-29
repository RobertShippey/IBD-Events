<?php

function save_event_location_meta($post_id ) {

    $location_taxonomy = "ibde-location";

    $location = get_field('location');
    if ($location) {

        $lat = $location['lat'];
        $lng = $location['lng'];

        $location_data = get_location_details($lat, $lng);

        if (null !== $location_data) {

            update_post_meta($post_id, 'country_code', $location_data['country political']['short']);

            // country category
            $country_term = $location_data['country political']['long'];

            $country_term_taxonomy_ids = term_exists($country_term, $location_taxonomy);
            if ((0 !== $country_term_taxonomy_ids) && (null !== $country_term_taxonomy_ids) ) {

                $country_term_taxonomy_ids = (int) $country_term_taxonomy_ids['term_taxonomy_id'];
                wp_set_object_terms($post_id, $country_term_taxonomy_ids, $location_taxonomy, true);

            } else {

                $country_cat_defaults = array(
                    'cat_name' => $country_term,
                    'taxonomy' => $location_taxonomy);

                $wp_error = null;
                $country_new_cat_ID = wp_insert_category($country_cat_defaults, $wp_error);

                $country_term_taxonomy_ids = wp_set_object_terms($post_id, (int) $country_new_cat_ID, $location_taxonomy, true);
                $country_term_taxonomy_ids = $country_term_taxonomy_ids[0];
            }

            // admin area category
            if (array_key_exists('administrative_area_level_1 political', $location_data)) {

                $admin_area_term = $location_data['administrative_area_level_1 political']['long'];
            } else {

                $admin_area_term = $location_data['administrative_area_level_2 political']['long'];
            }

            $admin_term_taxonomy_ids = term_exists($admin_area_term, $location_taxonomy);
            if ($admin_term_taxonomy_ids !== 0 && $admin_term_taxonomy_ids !== null) {

                $admin_term_taxonomy_ids = (int) $admin_term_taxonomy_ids['term_taxonomy_id'];
                wp_set_object_terms($post_id, $admin_term_taxonomy_ids, $location_taxonomy, true);

            } else {

                $admin_cat_defaults = array(
                    'cat_name' => $admin_area_term,
                    'taxonomy' => $location_taxonomy,
                    'category_parent' => $country_term_taxonomy_ids
                );

                $wp_error = null;
                $admin_new_cat_ID = wp_insert_category($admin_cat_defaults, $wp_error);


                $admin_term_taxonomy_ids = wp_set_object_terms($post_id, (int) $admin_new_cat_ID, $location_taxonomy, true);
            $admin_term_taxonomy_ids = $admin_term_taxonomy_ids[0];
            }

            // admin area category
            $locality_term_name = $location_data['locality political']['long'];

            $locality_taxonomy_ids = term_exists($locality_term_name, $location_taxonomy);
            if ((0 !== $locality_taxonomy_ids) && (null !== $locality_taxonomy_ids)) {

                $locality_taxonomy_ids = (int) $locality_taxonomy_ids['term_taxonomy_id'];
                wp_set_object_terms($post_id, $locality_taxonomy_ids, $location_taxonomy, true);

            } else {

                $locality_cat_default = array(
                    'cat_name' => $locality_term_name,
                    'taxonomy' => $location_taxonomy,
                    'category_parent' => $admin_term_taxonomy_ids
                );

                $wp_error = null;
                $locality_new_cat_ID = wp_insert_category($locality_cat_default, $wp_error);

                $locality_taxonomy_ids = wp_set_object_terms($post_id, (int) $locality_new_cat_ID, $location_taxonomy, true);
              $locality_taxonomy_ids = $locality_taxonomy_ids[0];
            }
        }
    }
}

add_action('acf/save_post', 'save_event_location_meta', 30); 


function get_location_details($lat, $lng) {

    $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat . "," . $lng . "&sensor=false";
    $data = file_get_contents($url);

    if (false !== $data) {

        $jsondata = json_decode($data, true);
        if (is_array($jsondata) && $jsondata['status'] == "OK") {

            $data = array();
            foreach ($jsondata['results']['0']['address_components'] as $element) {
                $data[implode(' ', $element['types'])] = array("long" => $element['long_name'], "short" => $element['short_name']);
            }

            return $data;
        }
    }
    return null;
}
