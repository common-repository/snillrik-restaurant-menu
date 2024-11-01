<?php

/**
 * Plugin Name: Snillrik restaurant menu
 * Plugin URI: http://www.snillrik_restaurant.com/
 * Description: Snillrik restaurant restaurant menu handler.
 * Version: 2.1.2
 * Author: Mattias Kallio
 * Author URI: http://www.snillrik.se
 * License: GPL2
 * Requires at least: 4.5.2
 * Tested up to: 6.2
 * Text Domain: snrestaurantmenu
 * Domain Path: /languages/
 */

DEFINE("SNILLRIKRESTAURANT_VERSION", "2.1.0");
DEFINE("SILLRIKRESTAURANT_PLUGIN_DIR", plugin_dir_path(__FILE__));
DEFINE("SILLRIKRESTAURANT_PLUGIN_URL", plugin_dir_url(__FILE__));
DEFINE("SNILLRIKRESTAURANT_NAME", "snrestaurantmenu");
DEFINE("SNILLRIKRESTAURANT_REWRITE_DISH", "snillrik_lm_dish");
DEFINE("SNILLRIKRESTAURANT_REWRITE_MENU", "snillrik_lm_menu");


require_once(SILLRIKRESTAURANT_PLUGIN_DIR . 'classes/settings.php');
require_once(SILLRIKRESTAURANT_PLUGIN_DIR . 'classes/menu.php');
require_once(SILLRIKRESTAURANT_PLUGIN_DIR . 'classes/dish.php');
require_once(SILLRIKRESTAURANT_PLUGIN_DIR . 'classes/shortcodes.php');
require_once(SILLRIKRESTAURANT_PLUGIN_DIR . 'classes/widgets.php');

/**
 * Loading styles and scripts
 */
add_action('admin_enqueue_scripts', 'load_admin_style');
function load_admin_style()
{
	wp_register_style('snillrik_admin_css', plugins_url('css/snillrik_restaurant_admin.css', __FILE__),[],SNILLRIKRESTAURANT_VERSION);
	wp_enqueue_style('snillrik_admin_css');
	wp_enqueue_style('snillrik-admin-settings', SILLRIKRESTAURANT_PLUGIN_URL . 'css/settings-page.css');
	wp_register_script('snillrik_restaurant.js', plugins_url('js/snillrik_restaurant.js', __FILE__), ['jquery', 'postbox'], SNILLRIKRESTAURANT_VERSION);
	wp_enqueue_script('snillrik_restaurant.js');
}

function snillrik_restaurant_styles()
{
	wp_register_style('snillrik_restaurant', plugins_url('css/snillrik_restaurant.css', __FILE__),[],SNILLRIKRESTAURANT_VERSION);
	wp_register_script('snillrik_restaurant_front', plugins_url('js/snillrik_restaurant_front.js', __FILE__), ['jquery'], SNILLRIKRESTAURANT_VERSION);
}

add_action('wp_enqueue_scripts', 'snillrik_restaurant_styles');

function snillrik_restaurant_plugin_init()
{
	load_plugin_textdomain(SNILLRIKRESTAURANT_NAME, false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'snillrik_restaurant_plugin_init');
