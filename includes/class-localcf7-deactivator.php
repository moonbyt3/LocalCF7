<?php

/**
 * Fired during plugin deactivation
 *
 * @link       www.decem.co
 * @since      1.0.0
 *
 * @package    Localcf7
 * @subpackage Localcf7/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Localcf7
 * @subpackage Localcf7/includes
 * @author     Decem <info@decem.co>
 */
class Localcf7_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		// 	exit;
		// 	global $wpdb;
		// 	$table_name = $wpdb->prefix . "LocalCF7";
		// 	$sql = "DROP TABLE IF EXISTS $table_name;";
		// 	$wpdb->query($sql);
		// 	delete_option("my_plugin_db_version");
		// }
	}

}
