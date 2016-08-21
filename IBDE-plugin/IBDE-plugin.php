<?php 
/*
Plugin Name: IBD Events Functionality
*/

$PLUGINPATH = plugin_dir_path( __FILE__ );

require_once ($PLUGINPATH . '_keys.php');

require_once ($PLUGINPATH . 'event-class.php');
require_once ($PLUGINPATH . 'timezones.php');

require_once ($PLUGINPATH . 'helper-functions.php');
require_once ($PLUGINPATH . 'comments.php');
require_once ($PLUGINPATH . 'dashboard.php');

require_once ($PLUGINPATH . 'archives.php');

require_once ($PLUGINPATH . 'save-event-hook.php');

require_once ($PLUGINPATH . 'ical-generator.php');

require_once ($PLUGINPATH . 'post-type.php');
register_activation_hook( __FILE__, 'ibde_add_roles_on_plugin_activation' );


require_once ($PLUGINPATH . 'plugin-activation/plugin-activation-config.php');

require_once ($PLUGINPATH . 'stripe-api/init.php');

require_once ($PLUGINPATH . 'cleanup-things.php');

require_once ($PLUGINPATH . 'opengraph.php');

require_once ($PLUGINPATH . 'weather.php');

require_once ($PLUGINPATH . 'cron.php');

require_once ($PLUGINPATH . 'news.php');