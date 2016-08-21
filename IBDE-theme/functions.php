<?php
// ***********************************************************************//
// External Dependancies
// ***********************************************************************//

// Navwalker - Makes the WordPress menu have the same classes as bootstrap
require_once (TEMPLATEPATH . '/inc/navwalker/wp_bootstrap_navwalker.php');

// ***********************************************************************//
// Lingo Administration Menu
// ***********************************************************************//
// Lingo Admin Menu - Include the menu index
require_once (TEMPLATEPATH . '/inc/lingoadmin/index.php');

// ***********************************************************************//
// Base Theme Functions
// ***********************************************************************//
require_once (TEMPLATEPATH . '/inc/themefunctions/admin.php');
require_once (TEMPLATEPATH . '/inc/themefunctions/excerpts.php');
require_once (TEMPLATEPATH . '/inc/themefunctions/images.php');
require_once (TEMPLATEPATH . '/inc/themefunctions/menus.php');
require_once (TEMPLATEPATH . '/inc/themefunctions/scripts.php');
require_once (TEMPLATEPATH . '/inc/themefunctions/styles.php');
require_once (TEMPLATEPATH . '/inc/themefunctions/widgets.php');
require_once (TEMPLATEPATH . '/inc/themefunctions/woocommerce.php');

// ***********************************************************************//
// Optional Theme Functions
// ***********************************************************************//
//require_once (TEMPLATEPATH . '/inc/themefunctions/crapstripper.php');