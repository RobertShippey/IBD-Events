<?php 
/**
 * Plugin Name: IBD Events Functionality
 */

$plugin_path = plugin_dir_path( __FILE__ );

// Imports API and other keys
require_once ($plugin_path . '_keys.php');

require_once ($plugin_path . 'event-class.php');
require_once ($plugin_path . 'timezones.php');
require_once ($plugin_path . 'helper-functions.php');
require_once ($plugin_path . 'comments.php');
require_once ($plugin_path . 'dashboard.php');
require_once ($plugin_path . 'archives.php');
require_once ($plugin_path . 'save-event-hook.php');
require_once ($plugin_path . 'ical-generator.php');

require_once ($plugin_path . 'post-type.php');
register_activation_hook( __FILE__, 'ibde_add_roles_on_plugin_activation' );

require_once ($plugin_path . 'plugin-activation/plugin-activation-config.php');
require_once ($plugin_path . 'stripe-api/init.php');
require_once ($plugin_path . 'cleanup-things.php');
require_once ($plugin_path . 'opengraph.php');
require_once ($plugin_path . 'weather.php');
require_once ($plugin_path . 'cron.php');
require_once ($plugin_path . 'taxonomy-count.php');
require_once ($plugin_path . 'news.php');
require_once ($plugin_path . 'admin_sort.php');
require_once ($plugin_path . 'json-ld.php');
