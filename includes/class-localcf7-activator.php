<?php

/**
 * Fired during plugin activation
 *
 * @link       www.decem.co
 * @since      1.0.0
 *
 * @package    Localcf7
 * @subpackage Localcf7/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Localcf7
 * @subpackage Localcf7/includes
 * @author     Decem <info@decem.co>
 */
class Localcf7_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$version = get_option( 'my_plugin_version', '1.0' );

		$table_name = $wpdb->prefix . "LocalCF7";
		$charset_collate = $wpdb->get_charset_collate();

		
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) AUTO_INCREMENT primary key NOT NULL,
			title VARCHAR(127) CHARACTER SET utf8,
			postedAt DATETIME,
			formData BLOB
		) $charset_collate;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		
		
	}
}
