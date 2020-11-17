<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.decem.co
 * @since             1.0.0
 * @package           Localcf7
 *
 * @wordpress-plugin
 * Plugin Name:       LocalCF7
 * Plugin URI:        www.decem.co
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Decem
 * Author URI:        www.decem.co
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       localcf7
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

global $wpdb;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'LOCALCF7_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-localcf7-activator.php
 */
function activate_localcf7() {	
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-localcf7-activator.php';
	Localcf7_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-localcf7-deactivator.php
 */
function deactivate_localcf7() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-localcf7-deactivator.php';
	Localcf7_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_localcf7' );
register_deactivation_hook( __FILE__, 'deactivate_localcf7' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-localcf7.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_localcf7() {

	$plugin = new Localcf7();
	$plugin->run();

}
run_localcf7();
