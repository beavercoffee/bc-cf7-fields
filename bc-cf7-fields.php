<?php
/*
Author: Beaver Coffee
Author URI: https://beaver.coffee
Description: Filter Contact Form 7 fields.
Domain Path:
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network: true
Plugin Name: BC CF7 Fields
Plugin URI: https://github.com/beavercoffee/bc-cf7-fields
Requires at least: 5.7
Requires PHP: 5.6
Text Domain: bc-cf7-fields
Version: 1.7.13.1
*/

if(defined('ABSPATH')){
    define('BC_CF7_FIELDS', __FILE__);
    require_once(plugin_dir_path(BC_CF7_FIELDS) . 'classes/class-bc-cf7-fields.php');
    BC_CF7_Fields::get_instance(BC_CF7_FIELDS);
}
