<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.localhost443.com
 * @since      1.0.0
 *
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Htgdropshipping
 * @subpackage Htgdropshipping/includes
 * @author     Sakhawat Hossen <admin@localhost443.com>
 */
class Htgdropshipping
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Htgdropshipping_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('HTGDROPSHIPPING_VERSION')) {
			$this->version = HTGDROPSHIPPING_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'htgdropshipping';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Htgdropshipping_Loader. Orchestrates the hooks of the plugin.
	 * - Htgdropshipping_i18n. Defines internationalization functionality.
	 * - Htgdropshipping_Admin. Defines all hooks for the admin area.
	 * - Htgdropshipping_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-htgdropshipping-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-htgdropshipping-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-htgdropshipping-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-htgdropshipping-public.php';
		/**
		 * requiring FetchAndUpload file
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/FetchAndUpload.php';

		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-htgdropshipping-public.php';
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-htgdropshipping-public.php';

		$this->loader = new Htgdropshipping_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Htgdropshipping_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{



		$plugin_i18n = new Htgdropshipping_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Htgdropshipping_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		/**
		 * Defining A schedule to update data in every 24 hours
		 */
		$this->loader->add_action('private_htg_fetch', $plugin_admin, 'update_htg_product');

		
		/**
		 * Adding hook for admin menu
		 */

		$this->loader->add_action('admin_menu', $plugin_admin, 'add_admin_menu');


		

		/**
		 * Registering HTG API settings
		 * 
		 * @since    1.0.0
		 */
		$this->loader->add_action('admin_init', $plugin_admin, 'register_hgt_general_settings');
		/**
		 * Registering HTG Woocommerce Settings
		 * 
		 * @since    1.0.0
		 */
		$this->loader->add_action('admin_init', $plugin_admin, 'register_hgt_woo_general_settings');
		/**
		 * Registering HTG Default Settings
		 * 
		 * @since    1.0.0
		 */
		$this->loader->add_action('admin_init', $plugin_admin, 'register_hgt_default_settings');

		/**
		 * Adding Hook to for woocommerce Order
		 * 
		 * @since    1.0.0
		 */
		if (get_option("hgtDefaultOrderStatus")) {
			$val = get_option("hgtDefaultOrderStatus");
			if ($val == 1) {
				$this->loader->add_action('woocommerce_order_status_pending', $plugin_admin, 'htg_order_processing');
			} elseif ($val == 2) {
				$this->loader->add_action('woocommerce_order_status_failed', $plugin_admin, 'htg_order_processing');
			} elseif ($val == 3) {
				$this->loader->add_action('woocommerce_order_status_processing', $plugin_admin, 'htg_order_processing');
			} elseif ($val == 4) {
				$this->loader->add_action('woocommerce_order_status_on-hold', $plugin_admin, 'htg_order_processing');
			} elseif ($val == 5) {
				$this->loader->add_action('woocommerce_order_status_completed', $plugin_admin, 'htg_order_processing');
			} elseif ($val == 6) {
				$this->loader->add_action('woocommerce_order_status_refunded', $plugin_admin, 'htg_order_processing');
			} else {
				$this->loader->add_action('woocommerce_order_status_processing', $plugin_admin, 'htg_order_processing');
			}
		} else {
			$this->loader->add_action('woocommerce_order_status_processing', $plugin_admin, 'htg_order_processing');
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		$plugin_public = new Htgdropshipping_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('rest_api_init', $plugin_public, 'my_custom_api');


		$this->loader->add_action('public_schedule_fetch', $plugin_public, 'public_fetch');

		/**
		 * Hooking into every 10 minutes
		 */
		// $this->loader->add_action('private_htg_fetch', $plugin_public, 'update_htg_product');

		// $this->loader->add_action( 'rest_api_init', $plugin_public, 'my_custom_api');
		/**
		 * Adding ShortCode
		 * @since    1.0.0
		 */
		$this->loader->add_shortcode('htgplugin_show_order',  $plugin_public, 'get_product_data_button');
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Htgdropshipping_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
