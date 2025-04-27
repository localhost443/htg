<?php

/**
 * @link              https://www.localhost443.com
 * @since             1.0.0
 * @package           Htgdropshipping
 *
 * @wordpress-plugin
 * Plugin Name:       HTG DropShipping
 * Plugin URI:        https://www.localhost443.com
 * Description:       A Plugin to sync data from HTG to Wordpress
 * Version:           1.0.0
 * Author:            Sakhawat Hossen <localhost800@gmail.com>
 * Author URI:        https://www.localhost443.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       htgdropshipping
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('HTGDROPSHIPPING_VERSION', '1.0.0');

add_action('wp_enqueue_scripts', 'htgdropshipping');

function htgdropshipping()
{

	wp_localize_script('htgdropshipping_javascript', 'htgdropshipping_data', array(
		'root_url' => get_site_url(),
		'nonce' => wp_create_nonce('wp_rest'),
	));
}
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-htgdropshipping-activator.php
 */
function activate_htgdropshipping()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-htgdropshipping-activator.php';
	Htgdropshipping_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-htgdropshipping-deactivator.php
 */
function deactivate_htgdropshipping()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-htgdropshipping-deactivator.php';
	Htgdropshipping_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_htgdropshipping');
register_deactivation_hook(__FILE__, 'deactivate_htgdropshipping');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-htgdropshipping.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_htgdropshipping()
{

	$plugin = new Htgdropshipping();
	$plugin->run();
}
run_htgdropshipping();
