<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.localhost443.com
 * @since      1.0.0
 *
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/public
 */

use function GuzzleHttp\json_decode;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/public
 * @author     Sakhawat Hossen <admin@localhost443.com>
 */
class Htgdropshipping_Public
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/htgdropshipping-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/htgdropshipping-public.js', array('jquery'), $this->version, true);

		wp_localize_script($this->plugin_name, 'htgdropshipping_data' , array(
			'root_url' => get_site_url(),
			'nonce' => wp_create_nonce('wp_rest'),
		));
	}

	public function my_custom_api()
	{
		/**
		 * Sync the product immediately 
		 */
		register_rest_route('htgdropshipping/v1/', 'sync', array(
			'methods' => 'POST',
			'callback' => array($this, 'syncrequest'),
		));

		/**
		 * ORDER GROUP
		 */
		register_rest_route('htgdropshipping/v1', '/singleOrder/(?P<order>[\S]+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'checkorder'),
		));

		register_rest_route('htgdropshipping/v1/', 'allOrders', array(
			'methods' => 'GET',
			'callback' => array($this, 'checkorder'),
		));
	}


	public function checkorder(WP_REST_Request $request)
	{
		$requests = $request->get_url_params();
		$orderNumber = $requests['order'];
	}

	public function syncrequest($requests)
	{
		// @ini_set("max_execution_time", 600000);
		// if ( current_user_can( 'manage_options' ) ) {
		// 	return 'Administrator Found';
		// } else {
		// 	return "You Are Not An Administrator";
		// }

		// return ABSPATH . "\wp-content\plugins\htgdropshipping\public\textfile.txt";
		// shell_exec('curl http://localhost/lol.php');
		// wp_schedule_single_event(time(), 'public_schedule_fetch', array(time()));
		
		wp_schedule_single_event(time(), 'public_schedule_fetch', array());
		// sleep(3);
		// shell_exec('curl http://localhost/htg/wp-cron.php?setcronjob');
		if ( ! wp_next_scheduled( 'private_htg_fetch' ) ) {
			wp_schedule_event( time(), 'hourly', 'private_htg_fetch' );
		}

		// wp_schedule_event( time(), 'hourly', 'private_htg_fetch', array(time()) );
		// sleep(3);
		// shell_exec('curl http://localhost/htg/wp-cron.php?setcronjob');
		// return "Event has been scheduled";
		// return true;
		// return wp_get_schedules();
		// error_log('I fuck you');
		// return 'scheduled';
		// return 'hello world';
		// $this->public_fetch();
		// $this->update_htg_product();
	}

	public function public_fetch()
	{

		$data = new FetchAndUpload();
		// $data->uploadProductTest();
		$data->queryBuilder();
	}

	public function update_htg_product() {
		require_once("UpdateAndUpload.php");
		$data = new UpdateAndUpload();
		// var_dump($data->fetch());
		$data->queryBuilder();
		
	}

	/**
	 * Public Shortcode To check Product Update
	 */
	public function get_product_data_button()
	{
		// return "Hello World";
		?>
		<p>Check you HTG Order here</p>
		<input id="getGtgOrderId" type="text" name="" id="">
		<br>
		<button style="padding: 2px;" id='getGtgOrderIdbutton' >Check Order</button>
		 <?php
	}

	
}
