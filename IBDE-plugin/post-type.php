<?php


// fix authors viewing others posts
// http://wordpress.stackexchange.com/questions/34765/help-to-condense-optimize-some-working-code

// seperate out 'creating post type' and 'permissions' maybe


function ibde_add_roles_on_plugin_activation() {
  remove_role( 'subscriber' );
  remove_role( 'editor' );
  remove_role( 'author' );
  remove_role( 'contributor' );

  add_role('event_author', 'Event Author', array (
   'read' => true
   ));

  add_role('event_moderator', 'Moderator', array (
    'read' => true
    ));

  add_role('event_super_mod', 'Super-Mod', array (
    'read' => true
    ));

}


// -----------------------------------------
//  Creation of the Events custom post type
// -----------------------------------------

  add_action( 'init', 'ibde_register_my_cpts' );

  function ibde_register_my_cpts() {
  	$labels = array(
  		"name" => "Events",
  		"singular_name" => "Event",
  		);

  	$args = array(
  		"labels" => $labels,
  		"description" => "IBD Events",
  		"public" => true,
  		"show_ui" => true,
  		"has_archive" => true,
  		"show_in_menu" => true,
  		"exclude_from_search" => false,
  		"capability_type" => "ibde-event",
  		"map_meta_cap" => true,
  		"hierarchical" => false,
  		"rewrite" => array( 
        "slug" => "event", 
        "with_front" => true,
        "ep_mask" => EP_PERMALINK ),
  		"query_var" => "event",
  		"menu_position" => 5,		
      'menu_icon' => "dashicons-tickets-alt",
      "supports" => array( "title", "editor", "excerpt", "revisions", "thumbnail", "author" ),		
      );
  	register_post_type( "ibde-event", $args );


// -------------------
//  Category Taxonomy 
// -------------------

  	$cat_labels = array(
  		"name" => "Categories",
  		"label" => "Categories",
  		);

  	$cat_args = array(
  		"labels" => $cat_labels,
  		"hierarchical" => true,
  		"label" => "Categories",
  		"show_ui" => true,
  		"query_var" => true,
  		"rewrite" => array( 
        'slug' => 'categories', 
        'with_front' => true,
        "ep_mask" => EP_CATEGORIES ),
  		"show_admin_column" => true,
      // // defaults should be fine... 
  		// 'capabilities' => array(
  		// 	'manage_terms' => 'manage_ibde-event-cats',
  		// 	'edit_terms' => 'edit_ibde-event-cats',
  		// 	'delete_terms' => 'delete_ibde-event-cats',
  		// 	'assign_terms' => 'assign_ibde-event-cats'
  		// 	)
  		);

  	register_taxonomy( "ibde-category", array( "ibde-event" ), $cat_args );



//  -------------------
//   Location Taxonomy
//  -------------------

    $location_tax_labels = array(
      "name" => "Locations",
      "label" => "Locations",
      );

    $location_tax_args = array(
      "labels" => $location_tax_labels,
      "hierarchical" => true,
      "label" => "Locations",
      "show_ui" => true,
      "query_var" => true,
      "rewrite" => array( 
      'slug' => 'locations', 
      'with_front' => true,
      "ep_mask" => EP_CATEGORIES ),
      "show_admin_column" => true,
      );
    
    register_taxonomy( "ibde-location", array( "ibde-event" ), $location_tax_args );



// ------------------------
//  Capabilities on Events
// ------------------------

    $eventCPT = get_post_type_object('ibde-event');
      //$post_type_cap  = $eventCPT->capability_type;
    $eventCaps = $eventCPT->cap;


// Get Administrator
$admin = get_role( 'administrator');
$admin->add_cap( $eventCaps->edit_posts );
$admin->add_cap( $eventCaps->edit_others_posts );
$admin->add_cap( $eventCaps->publish_posts );
$admin->add_cap( $eventCaps->read_private_posts );
$admin->add_cap( $eventCaps->read);
$admin->add_cap( $eventCaps->delete_posts);
$admin->add_cap( $eventCaps->delete_private_posts);
$admin->add_cap( $eventCaps->delete_published_posts );
$admin->add_cap( $eventCaps->delete_others_posts);
$admin->add_cap( $eventCaps->edit_private_posts);
$admin->add_cap( $eventCaps->edit_published_posts);



$author = get_role( 'event_author');
$author->add_cap( $eventCaps->edit_posts );



$mod = get_role( 'event_moderator');
$mod->add_cap( $eventCaps->edit_posts );
$mod->add_cap( $eventCaps->edit_others_posts );
$mod->add_cap( $eventCaps->publish_posts );
$mod->add_cap( $eventCaps->edit_published_posts );




$super_mod = get_role( 'event_super_mod');
$admin->add_cap( $eventCaps->edit_posts );
$admin->add_cap( $eventCaps->edit_others_posts );
$admin->add_cap( $eventCaps->publish_posts );
$admin->add_cap( $eventCaps->read_private_posts );
$admin->add_cap( $eventCaps->read);
$admin->add_cap( $eventCaps->delete_posts);
$admin->add_cap( $eventCaps->delete_private_posts);
$admin->add_cap( $eventCaps->delete_published_posts );
$admin->add_cap( $eventCaps->delete_others_posts);
$admin->add_cap( $eventCaps->edit_private_posts);
$admin->add_cap( $eventCaps->edit_published_posts);



}
