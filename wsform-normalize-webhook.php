<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://juvo-design.de
 * @since             1.0.0
 * @package           WSForm_Normalize_Webhook
 *
 * @wordpress-plugin
 * Plugin Name:       WSForm Normalize Webhook
 * Plugin URI:        https://github.com/JUVOJustin/wsform-normalize-webhook
 * Description:       This plugin has the sole intention of providing a better webhook format than WSForm does by itself.
 * Version:           1.0.2
 * Author:            Justin Vogt
 * Author URI:        https://juvo-design.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wsform-normalize_webhook
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
use WSForm_Normalize_Webhook\Activator;
use WSForm_Normalize_Webhook\Deactivator;
use WSForm_Normalize_Webhook\WSForm_Normalize_Webhook;

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin absolute path
 */
define( 'WSFORM_NORMALIZE_WEBHOOK_PATH', plugin_dir_path( __FILE__ ) );
define( 'WSFORM_NORMALIZE_WEBHOOK_URL', plugin_dir_url( __FILE__ ) );

/**
 * Use Composer PSR-4 Autoloading
 */
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * The code that runs during plugin activation.
 */
function activate_wsform_normalize_webhook() {
    Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wsform_normalize_webhook() {
    Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wsform_normalize_webhook' );
register_deactivation_hook( __FILE__, 'deactivate_wsform_normalize_webhook' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wsform_normalize_webhook() {

	$version = "1.0.2";
	$plugin = new WSForm_Normalize_Webhook($version);
	$plugin->run();

}
run_wsform_normalize_webhook();

$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/JUVOJustin/wsform-normalize-webhook',
    __FILE__,
    'wsform-normalize_webhook'
);
$myUpdateChecker->getVcsApi()->enableReleaseAssets();