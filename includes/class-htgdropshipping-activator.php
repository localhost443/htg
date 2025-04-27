<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.localhost443.com
 * @since      1.0.0
 *
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/includes
 * @author     Sakhawat Hossen <admin@localhost443.com>
 */
class Htgdropshipping_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate()
	{
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name = $wpdb->prefix . "htgproductlist";
		$sql = "
			CREATE TABLE IF NOT EXISTS $table_name (
    		id INT NOT NULL AUTO_INCREMENT , 
    		remote_product_id VARCHAR(255) NULL , 
			remote_product_article VARCHAR(255) NULL , 
			local_product_id VARCHAR(255) NULL , 
			last_sync_time TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, 
			status VARCHAR(255) NULL , 
			stock INT DEFAULT '0' , 
			price DECIMAL NULL , 
			product_url TEXT NULL,
			wp_htgproductlist ADD `PD` VARCHAR(255) NULL
			currency VARCHAR(10) NULL , 
			PRIMARY KEY  (id))
		";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		$vat = dbDelta($sql);
		$table_name2 = $wpdb->prefix . "htgorderlist";
		$sql = "
			CREATE TABLE IF NOT EXISTS $table_name2 (
			id INT NOT NULL AUTO_INCREMENT , 
			name VARCHAR(255) NULL,
			local_order INT, 
			remote_order_info TEXT NULL,
			remote_order VARCHAR(255) NULL DEFAULT NULL, 
			local_order_status VARCHAR(255) NULL , 
			remote_order_status VARCHAR(255) NULL , 
			order_info TEXT NULL , 
			status VARCHAR(255) NULL , 
			stopped VARCHAR(15) NULL , 
			order_creation_time TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, 
			pickup_delivery_date TIMESTAMP NULL, 
			order_update_time TIMESTAMP NULL DEFAULT null, 
			PRIMARY KEY  (id))		
		";
		$wpdb->show_errors();
		$vat = dbDelta($sql);
		// var_dump($vat);
		// die();
	}
}
