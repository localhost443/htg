<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.localhost443.com
 * @since      1.0.0
 *
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/admin
 */

use GuzzleHttp\Psr7\Request;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/admin
 * @author     Sakhawat Hossen <admin@localhost443.com>
 */
class Htgdropshipping_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Htgdropshipping_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Htgdropshipping_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/htgdropshipping-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Htgdropshipping_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Htgdropshipping_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/htgdropshipping-admin.js', array('jquery'), $this->version, true);

		wp_localize_script($this->plugin_name, 'htgdropshipping_data', array(
			'root_url' => get_site_url(),
			'nonce' => wp_create_nonce('wp_rest'),
		));
	}

	/**
	 * Register the Custom menu for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function add_admin_menu()
	{
		add_menu_page('HTG Dropshipping Settings', 'HTG DropShipping', 'manage_options', 'htg-dropshipping.php', array($this, 'htg_display'), 'dashicons-buddicons-topics', 350);
		add_submenu_page('htg-dropshipping.php', 'HTG Tracker', 'HTG Tracker', 'manage_options', 'hgt-tracker', array($this, 'hgt_tracker'), 1);
		add_submenu_page('htg-dropshipping.php', 'HTG Orders', 'HTG Orders', 'manage_options', 'hgt-orders', array($this, 'hgt_orders'), 2);
		// add_submenu_page('', 'HTG Orders', 'HTG Orders', 'manage_options', 'hgt-orders', array($this, 'hgt_orders'), 2);
		// add_submenu_page('htg-dropshipping.php', 'HTG Setttings', 'HTG Setttings', 'manage_options', 'hgt-settings', array($this, 'hgt_settings'), 3);
	}
	/**
	 * Displaying Custom Page in the Menu
	 *
	 * @since    1.0.0
	 */

	public function htg_display()
	{
		require_once('partials/htgdropshipping-admin-display.php');
	}
	public function hgt_tracker()
	{
		require_once('partials/htgdropshipping-admin-tracker.php');
	}
	public function hgt_settings()
	{
		require_once('partials/htgdropshipping-admin-display.php');
	}
	public function hgt_orders()
	{
		require_once('partials/htgdropshipping-admin-orders.php');
	}


	/**
	 * Registering General HTG Settings
	 * 
	 * @since    1.0.0
	 */
	public function register_hgt_general_settings()
	{
		register_setting('htgCustomApiSettings', 'htgmainurl');
		register_setting('htgCustomApiSettings', 'htgkey');
		register_setting('htgCustomApiSettings', 'htgsecret');
		register_setting('htgCustomApiSettings', 'htgstockurl');
		register_setting('htgCustomApiSettings', 'htgstockcode');
		register_setting('htgCustomApiSettings', 'htgstockkey');
	}

	/**
	 * Registering HGT Woocomemrce Settings
	 * 
	 * @since    1.0.0
	 */
	public function register_hgt_woo_general_settings()
	{
		register_setting('htgCustomWoocommerceApiSettings', 'hgtwoocommercekey');
		register_setting('htgCustomWoocommerceApiSettings', 'hgtwoocommercepassword');
	}

	/**
	 * Registering HTG default Settings
	 * @return void 
	 */
	public function register_hgt_default_settings()
	{
		register_setting('htgDefaultSettings', 'hgtDefaultlebelUrl');
		register_setting('htgDefaultSettings', 'hgtDefaultDeliveryOption');
		register_setting('htgDefaultSettings', 'hgtDefaultOrderStatus');
	}

	/**
	 * Executing When Order going to be processing hehe
	 * @return void 
	 */

	public function htg_order_processing($orderId)
	{
		$shouldIExecute = false;
		global $wpdb;

		$shouldIExecute = false;


		$order = new WC_Order($orderId);
		$items = $order->get_items();
		// var_dump($items);
		$products = [];
		$get_product_ids = [];
		$order_customername = $order->get_billing_first_name() . " " .$order->get_billing_last_name();
		foreach ($items as $item) {
			$get_product_ids[] = $item['product_id'];
			$products[$item['product_id']] = [
				'name' => $item['name'],
				'local_id' => $item['product_id'],
				'qty' => $item['qty'],
				'totalPrice' => $item['total'],
				'tax' => $item['total_tax'],
				'total' => $item['total'] + ($item['total_tax'] ?? 0),
				'url' => get_admin_url() . "post.php?post=" . $item['product_id'] . "&action=edit",
				'ishtg' => 0,
			];
			// $product_variation_id[] = $item['variation_id'];
		}
		$product_ids = implode(', ', $get_product_ids);
		$sql = "SELECT local_product_id, remote_product_id, remote_product_article FROM " . $wpdb->prefix . "htgproductlist WHERE local_product_id IN (" . $product_ids . ")";
		// $wpdb->show_errors();
		$htgProducts = $wpdb->get_results($sql);
		$htgProducts = json_decode(json_encode($htgProducts), true);
		if (count($htgProducts)) {
			foreach ($htgProducts as $product) {
				$products[$product['local_product_id']]['ishtg'] = 1;
				$products[$product['local_product_id']]['articleNumber'] = $product['remote_product_article'];
			}

			$table = $wpdb->prefix . 'htgorderlist';
			$data = json_encode($products);
			$sql = "
				INSERT INTO {$table}(local_order, name, local_order_status , order_info, order_creation_time , status)
		 		VALUES ('{$orderId}', '{$order_customername}', 'processing',  '{$data}', CURRENT_TIMESTAMP, 'created') ";
			$wpdb->show_errors();
			$wpdb->query($sql);
			$wpdb->flush();
		} else {
			echo "There is no HTG Products";
		}
		$wpdb->flush();


		// die();
	}
}
